<?php

namespace App\Models;

use App\Models\Courses;
use App\Models\Subjects;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teachers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'hire_date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subject() {
        return $this->belongsTo(Subjects::class);
    }

    public function courses() {
        return $this->hasMany(Courses::class, "teacher_id");
    }
}
