<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'city',
        'state',
        'otp',
        'otp_expires_at',
        'reset_token',
        'reset_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get user's purchases
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get user's test attempts
     */
    public function testAttempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    /**
     * Get user's video view limits
     */
    public function videoViewLimits()
    {
        return $this->hasMany(VideoViewLimit::class);
    }

    /**
     * Check if user has purchased a specific item
     */
    public function hasPurchased($purchasableType, $purchasableId): bool
    {
        return $this->purchases()
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $purchasableId)
            ->where('payment_status', 'completed')
            ->exists();
    }
}

