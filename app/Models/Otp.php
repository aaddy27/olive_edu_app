<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'otp',
        'is_verified',
        'expires_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public static function generate(string $phone, int $length = 6): self
    {
        // Delete old OTPs for this phone
        static::where('phone', $phone)->delete();

        // Generate new OTP
        $otp = static::create([
            'phone' => $phone,
            'otp' => str_pad(random_int(0, 999999), $length, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
        ]);

        return $otp;
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(string $otp): bool
    {
        return !$this->is_verified && !$this->isExpired() && $this->otp === $otp;
    }

    public function markAsVerified(): void
    {
        $this->update(['is_verified' => true]);
    }
}
