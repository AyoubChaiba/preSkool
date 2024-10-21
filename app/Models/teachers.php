<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hire_date',
        'subject_id',
        'class_id',
        'name',
        'phone_number',
        'class_teacher_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subject() {
        return $this->belongsTo(subjects::class, 'subject_id');
    }

    public function class() {
        return $this->belongsTo(Classes::class, 'class_id');
    }

}
