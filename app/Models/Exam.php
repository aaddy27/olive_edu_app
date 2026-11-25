<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_board_id',
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

        static::creating(function ($exam) {
            if (empty($exam->slug)) {
                $exam->slug = Str::slug($exam->name);
            }
        });
    }

    public function examBoard()
    {
        return $this->belongsTo(ExamBoard::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
