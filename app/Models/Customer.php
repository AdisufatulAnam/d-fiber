<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $ip_address
 * @property bool $is_active
 * @property int|null $package_id
 * @property string $qr_code
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'ip_address',
        'is_active',
        'package_id',
        'qr_code',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public static function generateQrCode(): string
    {
        do {
            $code = 'DFB-' . strtoupper(bin2hex(random_bytes(4)));
        } while (self::where('qr_code', $code)->exists());

        return $code;
    }
}
