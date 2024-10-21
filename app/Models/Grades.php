<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'grade',
        'class_id',
        'exam_id'
    ];

    public function student() {
        return $this->belongsTo(students::class, 'student_id');
    }


    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exams::class);
    }

}
