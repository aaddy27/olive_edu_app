<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Institute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'logo_path',
        'is_active',
        'is_lifetime',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_lifetime' => 'boolean',
        'expires_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($institute) {
            if (empty($institute->code)) {
                $institute->code = 'INST-' . strtoupper(Str::random(6));
            }
        });

        static::saved(function ($institute) {
            // If institute active status changed
            if ($institute->wasChanged('is_active')) {
                if (!$institute->is_active) {
                    // Deactivate all admins and teachers of this institute
                    User::where('institute_id', $institute->id)->update(['is_active' => false]);
                    // Revoke active sessions/tokens
                    foreach ($institute->users as $user) {
                        $user->tokens()->delete();
                    }
                } else {
                    // Reactivate all admins and teachers of this institute
                    User::where('institute_id', $institute->id)->update(['is_active' => true]);
                }
            }
        });
    }

    public function isExpired(): bool
    {
        if ($this->is_lifetime) {
            return false;
        }

        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return !$this->isExpired();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'institute_admin');
    }

    public function admin()
    {
        return $this->hasOne(User::class)->where('role', 'institute_admin')->latestOfMany();
    }

    public function teachers()
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        if (filter_var($this->logo_path, FILTER_VALIDATE_URL)) {
            return $this->logo_path;
        }

        return asset('storage/' . $this->logo_path);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
