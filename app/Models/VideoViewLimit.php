<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoViewLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'user_id',
        'max_views',
        'viewed_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'max_views' => 'integer',
        'viewed_count' => 'integer',
        'last_viewed_at' => 'datetime',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementView(): void
    {
        $this->increment('viewed_count');
        $this->update(['last_viewed_at' => now()]);
    }

    public function hasViewsRemaining(): bool
    {
        return $this->viewed_count < $this->max_views;
    }
}
