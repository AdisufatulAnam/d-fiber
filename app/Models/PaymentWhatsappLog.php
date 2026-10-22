<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Riwayat pengiriman WhatsApp (nota/invoice) untuk sebuah pembayaran.
 *
 * @property int $id
 * @property int $payment_id
 * @property string $phone
 * @property string $status
 * @property int $attempts
 */
class PaymentWhatsappLog extends Model
{
    protected $fillable = [
        'payment_id',
        'phone',
        'status',
        'attempts',
        'message',
        'invoice_file',
        'error',
        'response',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
