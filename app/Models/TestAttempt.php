<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'started_at',
        'finished_at',
        'score',
        'answers',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'decimal:2',
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['started', 'in_progress']);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getDuration(): ?int
    {
        if ($this->started_at && $this->finished_at) {
            return $this->finished_at->diffInMinutes($this->started_at);
        }
        return null;
    }
}
