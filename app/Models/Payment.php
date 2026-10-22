<?php

namespace App\Models;

use App\Jobs\SendPaymentInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $customer_id
 * @property string $customer_name
 * @property string|null $billing_month
 * @property float $amount
 * @property string $payment_method
 * @property string|null $reference
 * @property string $status
 * @property string|null $notes
 */
class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'billing_month',
        'amount',
        'payment_method',
        'reference',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if ($payment->status === 'confirmed' && ! $payment->invoice_number) {
                $payment->invoice_number = static::generateInvoiceNumber();
            }
        });

        static::created(function (Payment $payment) {
            if ($payment->status === 'confirmed') {
                SendPaymentInvoice::dispatch($payment->id);
            }
        });

        static::updating(function (Payment $payment) {
            if ($payment->isDirty('status') && $payment->status === 'confirmed' && ! $payment->invoice_number) {
                $payment->invoice_number = static::generateInvoiceNumber();
            }
        });

        static::updated(function (Payment $payment) {
            if ($payment->wasChanged('status') && $payment->status === 'confirmed') {
                SendPaymentInvoice::dispatch($payment->id);
            }
        });
    }

    /**
     * Format: PAY + YYMMDD + 5 digit urut harian. Contoh: PAY26102200001
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'PAY' . now()->format('ymd');
        $count = static::where('invoice_number', 'like', $prefix . '%')->count();

        return $prefix . str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(PaymentWhatsappLog::class)->orderByDesc('created_at');
    }

    /**
     * Label bulan tagihan dalam Bahasa Indonesia, contoh: "Oktober 2026".
     */
    public function billingMonthLabel(): string
    {
        if (! $this->billing_month) {
            return '-';
        }

        [$year, $month] = explode('-', $this->billing_month);

        $names = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        return ($names[(int) $month] ?? '-') . ' ' . $year;
    }

    /**
     * Nominal dalam format Rupiah tanpa desimal, contoh: "Rp100.000".
     */
    public function amountLabel(): string
    {
        return 'Rp' . number_format((float) $this->amount, 0, ',', '.');
    }
}
