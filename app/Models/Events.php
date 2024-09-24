<?php

namespace App\Models;

use App\Models\Courses;
use App\Models\teachers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Events extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'course_id', 'teacher_id', 'start_time', 'end_time'];

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teachers::class);
    }

}
