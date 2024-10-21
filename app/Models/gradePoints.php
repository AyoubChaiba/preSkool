<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gradePoints extends Model
{
    use HasFactory;

    protected $fillable = [
        'mark_from',
        'mark_upto',
        'grade_name',
        'comment'
    ];
}
