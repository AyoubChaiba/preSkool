<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fees extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'status',
        'amount',
        'payment_date'
    ];

    public function student() {
        return $this->belongsTo(Students::class);
    }

    
}
