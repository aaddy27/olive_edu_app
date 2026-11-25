<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'title',
        'pdf_path',
        'description',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
