<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'phone_number',
        'gender'
    ];

    public function students() {
        return $this->hasMany(Students::class, 'parent_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function children(){
        return $this->hasMany(Students::class, 'parent_id');
    }


}
