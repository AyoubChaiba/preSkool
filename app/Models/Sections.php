<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section_name',
        'class_id',
        'capacity'
    ];

    public function students() {
        return $this->hasMany(students::class);
    }

    public function teacher() {
        return $this->belongsTo(Teachers::class, "class_teacher_id");
    }

    public function class() {
        return $this->belongsTo(Classes::class);
    }
}
