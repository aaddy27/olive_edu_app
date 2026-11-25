<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'subject_id',
        'question_text',
        'question_image',
        'options',
        'correct_option',
        'marks',
        'negative_marks',
        'explanation',
    ];

    protected $casts = [
        'options' => 'array',
        'marks' => 'decimal:2',
        'negative_marks' => 'decimal:2',
    ];

    protected $hidden = [
        'correct_option',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function isCorrect($answer): bool
    {
        return $this->correct_option === $answer;
    }
}
