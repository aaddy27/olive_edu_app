<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->name);
            }
        });
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subjects')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function notes()
    {
        return $this->hasMany(SubjectNote::class);
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
