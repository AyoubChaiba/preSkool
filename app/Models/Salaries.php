<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'status',
        'amount',
        'payment_date'
    ];


    public function teacher(){
        return $this->belongsTo(Teachers::class);
    }

}
