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
    ];

    public function user() {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function parent() {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

}
