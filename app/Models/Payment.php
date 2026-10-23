<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected $dates = [
        'paid_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get all status options.
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_PAID => 'Dibayar',
            self::STATUS_FAILED => 'Gagal',
            self::STATUS_REFUNDED => 'Dikembalikan',
            self::STATUS_EXPIRED => 'Kadaluarsa',
        ];
    }

    /**
     * Get the formatted status.
     *
     * @return string
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the amount formatted with currency.
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Check if payment is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment is paid.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }
}
