<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'course_id',
        'subject_id',
        'title',
        'slug',
        'test_number',
        'description',
        'instructions',
        'total_questions',
        'duration_minutes',
        'duration',
        'total_marks',
        'passing_marks',
        'negative_marking',
        'price',
        'is_free',
        'is_active',
        'is_test_series',
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'duration_minutes' => 'integer',
        'duration' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'negative_marking' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'is_test_series' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Test Series: Multiple subjects relationship
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'test_series_subjects')
                    ->withPivot('question_count')
                    ->withTimestamps();
    }

    // Parent-Child relationships for nested tests
    public function parent()
    {
        return $this->belongsTo(Test::class, 'parent_id');
    }

    public function childTests()
    {
        return $this->hasMany(Test::class, 'parent_id')->orderBy('test_number');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class);
    }

    public function purchases()
    {
        return $this->morphMany(Purchase::class, 'purchasable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    public function scopePaid($query)
    {
        return $query->where('price', '>', 0);
    }
}
