<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'day_name',
        'day_name_mm',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
