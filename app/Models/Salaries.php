<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'amount',
        'payment_date',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function teacher(){
        return $this->belongsTo(teachers::class);
    }
}
