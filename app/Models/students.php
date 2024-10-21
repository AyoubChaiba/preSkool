<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admission_date',
        'parent_id',
        'class_id',
        'section_id',
        'name',
        'phone_number',
        'date_of_birth',
        'address',
        'gender'
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }

    public function parent() {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function class() {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section() {
        return $this->belongsTo(Sections::class,'section_id');
    }

    public function grades()
    {
        return $this->hasMany(Grades::class, 'student_id');
    }

    public function attendance() {
        return $this->hasMany(Attendance::class,'student_id');
    }

    public function fees()
    {
        return $this->hasMany(Fees::class,'student_id');
    }

}
