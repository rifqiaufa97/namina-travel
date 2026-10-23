<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    // Order statuses
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    
    // Payment statuses
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PENDING_VERIFICATION = 'pending_verification';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REJECTED = 'rejected';
    const PAYMENT_EXPIRED = 'expired';
    const PAYMENT_CANCELLED = 'cancelled';
    const PAYMENT_REFUND = 'refund';

    protected $fillable = [
        'user_id',
        'destination_id',
        'invoice_number',
        'quantity',
        'total',
        'status',
        'start_date',
        'end_date',
        'departure_date',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'payment_status',
        'payment_proof',
        'payment_date',
        'expired_at',
        'paid_at',
        'cancelled_at',
        'pakasir_order_id',
        'pakasir_payment_method',
        'pakasir_payment_url',
        'pakasir_transaction_status',
    ];
    
    // Date fields are handled by the $casts array below

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * Scope a query to only include expired orders.
     */
    public function scopeExpired($query)
    {
        return $query->where('payment_status', self::PAYMENT_EXPIRED);
    }

    /**
     * Check if the order is paid.
     */
     public function isPaid(): bool
     {
         return $this->payment_status === self::PAYMENT_PAID;
     }

    /**
     * Check if the order is pending payment.
     */
    public function isPending(): bool
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    /**
     * Check if the order is pending verification.
     */
    public function isPendingVerification(): bool
    {
        return $this->payment_status === self::PAYMENT_PENDING_VERIFICATION;
    }

    /**
     * Check if the order is expired.
     */
    public function isExpired(): bool
    {
        return $this->payment_status === self::PAYMENT_EXPIRED;
    }

    /**
     * Mark the order as paid.
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'payment_status' => self::PAYMENT_PAID,
            'status' => self::STATUS_COMPLETED,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark the order as rejected.
     */
    public function markAsRejected(?string $reason = null): bool
    {
        $notes = $this->notes;
        if ($reason) {
            $notes = trim($notes . "\n[Alasan Penolakan: " . $reason . " - " . now()->format('d/m/Y H:i') . "]");
        }
        return $this->update([
            'payment_status' => self::PAYMENT_REJECTED,
            'notes' => $notes,
        ]);
    }

    /**
     * Mark the order as expired.
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'payment_status' => self::PAYMENT_EXPIRED,
            'status' => self::STATUS_EXPIRED,
            'expired_at' => now()
        ]);
    }

    /**
     * Mark the order as cancelled.
     */
    public function markAsCancelled(): bool
    {
        return $this->update([
            'payment_status' => self::PAYMENT_CANCELLED,
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now()
        ]);
    }

    /**
     * Check if the payment is expired.
     */
    public function isPaymentExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default payment status when creating a new order
        static::creating(function ($order) {
            if (empty($order->payment_status)) {
                $order->payment_status = self::PAYMENT_PENDING;
            }
            
            // Set expiry time (24 hours from now)
            $order->expired_at = now()->addHours(24);
        });

        // Check for expired orders
        static::saving(function ($order) {
            if ($order->isDirty('expired_at') && $order->isDirty('payment_status')) {
                return;
            }

            if ($order->expired_at && $order->expired_at->isPast() && $order->isPending()) {
                $order->markAsExpired();
            }
        });
    }

    protected $casts = [
        'departure_date' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the destination that the order is for.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Get all payments for the order.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all status options.
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PROCESSING => 'Sudah Bayar & Menunggu Konfirmasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    /**
     * Get all payment status options.
     *
     * @return array
     */
    public static function getPaymentStatusOptions()
    {
        return [
            self::PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::PAYMENT_PENDING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAYMENT_PAID => 'Lunas',
            self::PAYMENT_REJECTED => 'Pembayaran Ditolak',
            self::PAYMENT_CANCELLED => 'Dibatalkan',
            self::PAYMENT_EXPIRED => 'Kedaluwarsa',
        ];
    }

    /**
     * Get the formatted payment status.
     *
     * @return string
     */
    public function getFormattedPaymentStatusAttribute()
    {
        $statuses = self::getPaymentStatusOptions();
        return $statuses[$this->payment_status] ?? ucfirst(str_replace('_', ' ', $this->payment_status));
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
     * Get the total amount formatted with currency.
     *
     * @return string
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
