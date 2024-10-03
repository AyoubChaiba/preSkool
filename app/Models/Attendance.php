<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table ="attendance";

    protected $fillable = [
        'student_id',
        'course_id',
        'attendance_date',
        'status'
        ] ;

    public function student() {
        return $this->belongsTo(Students::class);
    }

    public function course() {
        return $this->belongsTo(Courses::class);
    }
}
