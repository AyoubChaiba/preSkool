<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_name',
        'exam_date',
        'subject_id',
        'class_id',
    ];

    public function subject() {
        return $this->belongsTo(subjects::class, 'subject_id');
    }

    public function class() {
        return $this->belongsTo(Classes::class, 'class_id');
    }



}
