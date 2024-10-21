<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'number_name'
    ];

    public function students() {
        return $this->hasMany(students::class,'class_id');
    }

    public function teachers() {
        return $this->hasMany(teachers::class,'class_id');
    }

    public function subjects() {
        return $this->hasMany(Subjects::class,'class_id');
    }

    public function sections() {
        return $this->hasMany(Sections::class,'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
