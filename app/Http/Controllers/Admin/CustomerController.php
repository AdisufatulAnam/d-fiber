<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Customer::with('package')->orderBy('name');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('qr_code', 'like', "%{$search}%");
            });
        }

        if (($status = $request->get('status')) !== null && $status !== '') {
            $query->where('is_active', $status === '1');
        }

        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $customers = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total' => Customer::count(),
            'active' => Customer::where('is_active', true)->count(),
            'inactive' => Customer::where('is_active', false)->count(),
        ];

        return Inertia::render('Admin/Customers/Index', [
            'admin' => $this->adminPayload($request),
            'customers' => $customers,
            'packages' => \App\Models\Package::orderBy('name')->get(['id', 'name', 'price', 'speed']),
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:45',
            'is_active' => 'boolean',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        $validated['qr_code'] = Customer::generateQrCode();
        Customer::create($validated);

        return back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'ip_address' => 'nullable|string|max:45',
            'is_active' => 'boolean',
            'package_id' => 'nullable|exists:packages,id',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->payments()->delete();
        $customer->delete();

        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }

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
