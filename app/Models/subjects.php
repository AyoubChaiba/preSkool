<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'subject_name'
    ];

    public function class() {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teachers() {
        return $this->hasMany(Teachers::class, 'subject_id');
    }

    public function grades()
    {
        return $this->hasMany(Grades::class);
    }

}
