<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\PaymentWhatsappLog;
use App\Services\GoWaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Kirim nota/invoice pembayaran via WhatsApp (GoWa) + lampiran PDF.
 * Berjalan di queue agar tidak memblokir request & mencegah spam.
 */
class SendPaymentInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries;

    /** Diisi saat handle berjalan (diambil dari DB, aman terhadap soft delete). */
    public Payment $payment;

    /** Delay antar percobaan ulang (detik) saat gagal. */
    public array $backoff = [30, 60, 120, 300, 600];

    /** ID pembayaran (bukan model) agar aman bila payment dihapus saat antrian. */
    public function __construct(public int $paymentId)
    {
        $this->tries = max(1, (int) config('wa.retries', 5));
    }

    public function handle(GoWaService $gowa): void
    {
        if (! config('wa.enabled')) {
            return;
        }

        $payment = Payment::withTrashed()->find($this->paymentId);

        // Payment sudah dihapus / tidak lagi lunas -> lewati tanpa dianggap gagal.
        if (! $payment || $payment->trashed() || $payment->status !== 'confirmed') {
            $this->delete();

            return;
        }

        $this->payment = $payment;
        $customer = $payment->customer()->with('package')->first();
        $phone = $gowa->normalizePhone($customer?->phone ?? null);

        // Belum ada nomor WhatsApp -> lewati tanpa dianggap gagal.
        if (! $phone) {
            $this->log('failed', $phone ?? '-', ['error' => 'Nomor WhatsApp pelanggan kosong.']);
            $this->delete();

            return;
        }

        $log = $this->findOrCreateLog($phone);

        $log->increment('attempts');
        $log->update(['status' => 'pending', 'error' => null]);

        // Bangun pesan (teks).
        $message = $this->buildMessage($customer, $phone);
        $log->update(['message' => $message]);

        // Lampirkan PDF hanya bila WA_SEND_DOCUMENT=true; selain itu kirim teks saja.
        if (config('wa.send_document')) {
            $pdfPath = $this->generatePdf($customer, $phone);
            $log->update(['invoice_file' => $pdfPath]);

            $filename = $this->invoiceFilename();

            $result = $gowa->sendDocument($phone, $message, Storage::path($pdfPath), $filename);
        } else {
            $result = $gowa->sendMessage($phone, $message);
        }

        if ($result['success']) {
            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
                'response' => $result['response'] ?? null,
                'error' => null,
            ]);

            // Bersihkan file PDF sementara (jika dibuat).
            if (config('wa.send_document') && isset($pdfPath)) {
                Storage::delete($pdfPath);
            }

            return;
        }

        $log->update([
            'status' => 'failed',
            'response' => $result['response'] ?? null,
            'error' => $result['error'] ?? 'Unknown error',
        ]);

        // Melempar exception agar queue mencoba lagi (retry dengan backoff).
        throw new \RuntimeException('Gagal kirim WhatsApp invoice: '.($result['error'] ?? 'unknown'));
    }

    public function failed(?Throwable $exception): void
    {
        // Dipanggil setelah semua percobaan habis -> tandai permanen gagal.
        PaymentWhatsappLog::where('payment_id', $this->paymentId)
            ->where('status', '!=', 'sent')
            ->update([
                'status' => 'failed',
                'error' => $exception?->getMessage() ?? 'Gagal setelah percobaan maksimum.',
            ]);
    }

    private function findOrCreateLog(string $phone): PaymentWhatsappLog
    {
        return PaymentWhatsappLog::firstOrCreate(
            ['payment_id' => $this->payment->id],
            ['phone' => $phone, 'status' => 'pending', 'attempts' => 0]
        );
    }

    private function log(string $status, string $phone, array $attrs = []): void
    {
        PaymentWhatsappLog::updateOrCreate(
            ['payment_id' => $this->payment->id],
            array_merge(['phone' => $phone, 'status' => $status], $attrs)
        );
    }

    private function buildMessage(?object $customer, string $phone): string
    {
        $brand = rtrim((string) config('app.url', ''), '/') ?: config('wa.brand', 'wiji.net');
        $periode = $this->payment->billingMonthLabel();
        $nama = $customer->name ?? $this->payment->customer_name;
        $id = $customer->qr_code ?: ($customer->id ?? '-');
        $total = $this->payment->amountLabel();

        $greeting = $this->greeting();

        return <<<MSG
{$greeting}

Kami dari *{$brand}* mengirimkan invoice pembayaran WiFi untuk periode *{$periode}*.

📄 *DETAIL PEMBAYARAN WIFI*
👤 Nama Pelanggan: {$nama}
🆔 ID Pelanggan: {$id}
📅 Periode Pembayaran: {$periode}
💰 Total Pembayaran: {$total}

📎 Invoice pembayaran kami lampirkan dalam bentuk file PDF pada pesan ini.

Terima kasih atas kepercayaan Bapak/Ibu dalam menggunakan layanan internet *{$brand}*. Semoga layanan kami selalu memberikan kenyamanan dan kelancaran dalam beraktivitas. 🙏🌐

Salam hangat,
*Admin {$brand}*
MSG;
    }

    private function greeting(): string
    {
        $hour = (int) now()->format('G');

        return match (true) {
            $hour < 11 => 'Assalamu’alaikum / Selamat pagi Bapak/Ibu 😊',
            $hour < 15 => 'Assalamu’alaikum / Selamat siang Bapak/Ibu 😊',
            $hour < 18 => 'Assalamu’alaikum / Selamat sore Bapak/Ibu 😊',
            default => 'Assalamu’alaikum / Selamat malam Bapak/Ibu 😊',
        };
    }

    /**
     * Nama file invoice: NamaPelanggan_NoInvoice.pdf
     */
    private function invoiceFilename(): string
    {
        $nama = preg_replace('/[^A-Za-z0-9]+/', '_', (string) ($this->payment->customer->name ?? $this->payment->customer_name));
        $nama = trim($nama, '_') ?: 'Pelanggan';
        $inv = $this->payment->invoice_number ?? ('INV'.$this->payment->id);

        return $nama.'_'.$inv.'.pdf';
    }

    private function generatePdf(?object $customer, string $phone): string
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            'payment' => $this->payment,
            'customer' => $customer,
            'phone' => $phone,
            'brand' => config('wa.brand', 'wiji.net'),
            'periode' => $this->payment->billingMonthLabel(),
            'tanggal' => now()->translatedFormat('d F Y'),
        ]);

        $dir = 'invoices';
        Storage::disk('local')->makeDirectory($dir);

        // Nama file tampil sebagai NamaPelanggan_NoInvoice.pdf
        $relative = $dir.'/'.$this->invoiceFilename();

        // Tulis konten PDF (bukan lewat putFileAs yang salah interpret string biner).
        Storage::disk('local')->put($relative, $pdf->output());

        return $relative;
    }
}
