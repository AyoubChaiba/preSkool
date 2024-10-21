<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'section_id'
    ];


    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teachers::class);
    }

    public function students() {
        return $this->hasManyThrough(Students::class, Sections::class, 'id', 'section_id', 'id', 'id');
    }

    public function section() {
        return $this->belongsTo(Sections::class);
    }

}
