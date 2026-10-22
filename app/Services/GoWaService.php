<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Client untuk gateway WhatsApp (GoWa).
 *
 * Base URL & endpoint diambil dari config/wa.php agar mudah dipindah
 * melalui .env tanpa menyentuh kode.
 */
class GoWaService
{
    /**
     * Normalisasi nomor Indonesia ke format internasional (62xxx).
     */
    public function normalizePhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if ($digits === '') {
            return null;
        }

        // 08xxx -> 628xxx
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }
        // 8xxx (tanpa awalan) -> 628xxx
        elseif (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        return $digits;
    }

    /**
     * Kirim pesan teks saja.
     *
     * @return array{success: bool, response?: string, error?: string}
     */
    public function sendMessage(string $phone, string $message): array
    {
        return $this->post(config('wa.endpoints.message'), [
            'phone' => $phone,
            'message' => $message,
        ]);
    }

    /**
     * Kirim pesan + lampiran file (PDF invoice).
     *
     * @return array{success: bool, response?: string, error?: string}
     */
    public function sendDocument(string $phone, string $message, string $filePath, string $filename): array
    {
        try {
            $response = Http::withBasicAuth(
                (string) config('wa.username'),
                (string) config('wa.password')
            )
                ->timeout((int) config('wa.timeout', 30))
                ->attach('file', file_get_contents($filePath), $filename, ['mime' => 'application/pdf'])
                ->post($this->endpoint(config('wa.endpoints.document')), [
                    'phone' => $phone,
                    'caption' => $message,
                ]);
        } catch (ConnectionException $e) {
            return $this->failure('Koneksi gagal: ' . $e->getMessage());
        } catch (Throwable $e) {
            return $this->failure('Exception: ' . $e->getMessage());
        }

        return $this->handle($response);
    }

    /**
     * POST JSON ke endpoint GoWa dengan basic auth.
     *
     * @return array{success: bool, response?: string, error?: string}
     */
    private function post(string $endpoint, array $payload): array
    {
        try {
            $response = Http::withBasicAuth(
                (string) config('wa.username'),
                (string) config('wa.password')
            )
                ->timeout((int) config('wa.timeout', 30))
                ->post($this->endpoint($endpoint), $payload);
        } catch (ConnectionException $e) {
            return $this->failure('Koneksi gagal: ' . $e->getMessage());
        } catch (Throwable $e) {
            return $this->failure('Exception: ' . $e->getMessage());
        }

        return $this->handle($response);
    }

    /**
     * @return array{success: bool, error: string}
     */
    private function failure(string $error): array
    {
        Log::warning('GoWa: pengiriman gagal', ['error' => $error]);

        return ['success' => false, 'error' => $error];
    }

    private function endpoint(string $path): string
    {
        return config('wa.base_url') . '/' . ltrim($path, '/');
    }

    /**
     * @param  \Illuminate\Http\Client\Response  $response
     * @return array{success: bool, response?: string, error?: string}
     */
    private function handle($response): array
    {
        $body = $response->body();

        if ($response->successful()) {
            return ['success' => true, 'response' => $this->truncate($body)];
        }

        Log::warning('GoWa: pengiriman gagal', [
            'status' => $response->status(),
            'body' => $this->truncate($body),
        ]);

        return [
            'success' => false,
            'response' => $this->truncate($body),
            'error' => 'HTTP ' . $response->status(),
        ];
    }

    private function truncate(string $value, int $limit = 500): string
    {
        return mb_strlen($value) > $limit ? mb_substr($value, 0, $limit) . '…' : $value;
    }
}
