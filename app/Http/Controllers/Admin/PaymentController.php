<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Halaman daftar pembayaran.
     */
    public function index(Request $request): Response
    {
        $query = Payment::with(['customer.package', 'whatsappLogs'])->orderBy('created_at', 'desc');

        if ($search = $request->get('search')) {
            $query->where('customer_name', 'like', "%{$search}%");
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($month = $request->get('month')) {
            $query->where('billing_month', $month);
        }

        if ($request->get('trashed') === 'only') {
            $query->onlyTrashed();
        } elseif ($request->get('trashed') === 'with') {
            $query->withTrashed();
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $payments = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Admin/Payments/Index', [
            'admin' => $this->adminPayload($request),
            'payments' => $payments,
            'customers' => Customer::with('package')->orderBy('name')->get(),
            'filters' => $request->only(['search', 'status', 'month', 'trashed']),
        ]);
    }

    /**
     * Scan QR code — cari pelanggan berdasarkan QR code.
     */
    public function scan(Request $request, string $qrCode): Response
    {
        $customer = Customer::where('qr_code', $qrCode)->first();

        if (! $customer) {
            abort(404, 'QR Code tidak ditemukan.');
        }

        $payments = Payment::where('customer_id', $customer->id)
            ->orderBy('billing_month', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Payments/Customer', [
            'admin' => $this->adminPayload($request),
            'customer' => $customer->load('package'),
            'payments' => $payments,
        ]);
    }

    /**
     * API: Cari pelanggan berdasarkan QR code (untuk AJAX).
     */
    public function findCustomerByQr(Request $request)
    {
        $request->validate(['qr_code' => 'required|string']);

        $customer = Customer::with('package')->where('qr_code', $request->qr_code)->first();

        if (! $customer) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found' => true,
            'customer' => $customer,
            'payments' => $this->yearBills($customer),
        ]);
    }

    /**
     * API: Ambil daftar tagihan milik satu pelanggan (untuk panel bayar).
     */
    public function customerBills(Customer $customer)
    {
        $customer->load('package');

        return response()->json([
            'customer' => $customer,
            'payments' => $this->yearBills($customer),
        ]);
    }

    /**
     * Susun tagihan 12 bulan terakhir sesuai paket pelanggan.
     * Bulan yang belum punya record dibuat sebagai tagihan pending.
     *
     * @return \Illuminate\Support\Collection<int, Payment>
     */
    private function yearBills(Customer $customer)
    {
        if ($customer->package) {
            $amount = (float) $customer->package->price;

            // -1 = bulan depan, 0 = bulan ini, 1..11 = bulan sebelumnya (12 bulan ke belakang + 1 ke depan)
            for ($i = -1; $i <= 11; $i++) {
                $month = now()->subMonthsNoOverflow($i)->format('Y-m');

                Payment::firstOrCreate(
                    [
                        'customer_id' => $customer->id,
                        'billing_month' => $month,
                    ],
                    [
                        'customer_name' => $customer->name,
                        'amount' => $amount,
                        'payment_method' => 'manual',
                        'status' => 'pending',
                    ]
                );
            }
        }

        return Payment::where('customer_id', $customer->id)
            ->orderBy('billing_month', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * API: Bayar beberapa tagihan sekaligus (multi bulan).
     */
    public function payBulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:payments,id',
            'payment_method' => 'nullable|in:qrcode,manual',
        ]);

        $payments = Payment::whereIn('id', $validated['ids'])
            ->where('status', 'pending')
            ->get();

        // Update satu per satu agar model event (kirim WA invoice) ikut terpicu.
        foreach ($payments as $payment) {
            $payment->update([
                'status' => 'confirmed',
                'payment_method' => $validated['payment_method'] ?? 'manual',
            ]);
        }

        return response()->json(['success' => true, 'count' => $payments->count()]);
    }

    /**
     * Simpan pembayaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'billing_month' => 'nullable|string|max:7',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:qrcode,manual',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = 'confirmed';
        Payment::create($validated);

        return back()->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Tambah tagihan bulanan untuk pelanggan.
     */
    public function addBill(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'billing_month' => 'required|string|max:7',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        Payment::create([
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'billing_month' => $validated['billing_month'],
            'amount' => $validated['amount'],
            'payment_method' => 'manual',
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Tagihan bulanan berhasil ditambahkan.');
    }

    /**
     * Update status pembayaran.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $payment->update($validated);

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    /**
     * Batalkan pembayaran (soft delete) — hanya untuk yang sudah lunas.
     */
    public function cancel(Payment $payment)
    {
        if ($payment->status !== 'confirmed') {
            return back()->withErrors(['payment' => 'Hanya pembayaran lunas yang dapat dibatalkan.']);
        }

        $payment->delete();

        return back()->with('success', 'Pembayaran dibatalkan.');
    }

    /**
     * Pulihkan pembayaran yang sudah dihapus (soft delete).
     */
    public function restore(Payment $payment)
    {
        if (! $payment->trashed()) {
            return back()->withErrors(['payment' => 'Data ini tidak dalam status terhapus.']);
        }

        $payment->restore();

        return back()->with('success', 'Pembayaran dipulihkan.');
    }

    /**
     * Kirim ulang nota/invoice WhatsApp untuk pembayaran yang lunas.
     */
    public function resendWhatsApp(Payment $payment)
    {
        if ($payment->status !== 'confirmed') {
            return back()->withErrors(['payment' => 'Hanya pembayaran lunas yang dapat dikirim ulang.']);
        }

        if (! config('wa.enabled')) {
            return back()->withErrors(['payment' => 'Fitur WhatsApp dinonaktifkan (WA_ENABLED=false).']);
        }

        \App\Jobs\SendPaymentInvoice::dispatch($payment->id);

        return back()->with('success', 'Pengiriman WhatsApp dijadwalkan ulang.');
    }

    /**
     * Payload admin untuk sidebar.
     */
    private function adminPayload(Request $request): array
    {
        $admin = $request->user();

        return [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
        ];
    }
}
