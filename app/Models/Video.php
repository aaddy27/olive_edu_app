<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_audience',
        'subject_id',
        'course_id',
        'playlist_id',
        'title',
        'description',
        'vimeo_video_id',
        'video_url',
        's3_key',
        'duration_seconds',
        'view_limit',
        'size_bytes',
        'sort_order',
        'is_preview',
        'is_free',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'is_free' => 'boolean',
        'is_active' => 'boolean',
        'duration_seconds' => 'integer',
        'view_limit' => 'integer',
        'size_bytes' => 'integer',
        'sort_order' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function viewLimits()
    {
        return $this->hasMany(VideoViewLimit::class);
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    public function scopeForStudents($query)
    {
        return $query->where('target_audience', 'student');
    }

    public function scopeForTeachers($query)
    {
        return $query->where('target_audience', 'teacher');
    }

    public function canView(User $user): bool
    {
        // Preview videos are always accessible
        if ($this->is_preview) {
            return true;
        }

        // Check if user has purchased the course
        if ($this->course_id && $user->hasPurchased(Course::class, $this->course_id)) {
            return true;
        }

        return false;
    }

    public function hasViewsRemaining(User $user): bool
    {
        $viewLimit = $this->viewLimits()->where('user_id', $user->id)->first();

        if (!$viewLimit) {
            return true;
        }

        return $viewLimit->viewed_count < $viewLimit->max_views;
    }

    public function getDurationFormatted(): string
    {
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
