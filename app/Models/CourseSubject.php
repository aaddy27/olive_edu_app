<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSubject extends Pivot
{
    protected $table = 'course_subjects';

    protected $fillable = [
        'course_id',
        'subject_id',
        'sort_order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
