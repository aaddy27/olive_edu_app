<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchasable_type',
        'purchasable_id',
        'amount',
        'currency',
        'payment_provider',
        'payment_status',
        'payment_meta',
        'order_id',
        'payment_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            if (empty($purchase->order_id)) {
                $purchase->order_id = 'ORD-' . strtoupper(Str::random(12));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchasable()
    {
        return $this->morphTo();
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'purchasable_id');
    }

    public function getStatusAttribute(): string
    {
        return $this->payment_status ?? 'pending';
    }

    public function getPaymentMethodAttribute(): string
    {
        return $this->payment_provider ?? 'Gateway';
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }
}
