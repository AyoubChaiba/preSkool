<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'teacher_id',
    ];

    public function subject() {
        return $this->belongsTo(subjects::class);
    }

    public function teacher() {
        return $this->belongsTo(teachers::class);
    }
}
