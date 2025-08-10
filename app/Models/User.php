<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // <--- Important
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    use HasFactory;

    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;

    public const MARRIED = true;
    public const SINGLE = false;

    protected $fillable = [
        'eid',
        'name',
        'email',
        'password',
        'position_id',
        'dob',
        'currentaddress',
        'phno',
        'department',
        'gender',
        'married_status',
        'image',
        'super_user',
    ];


    protected $casts = [
        'dob' => 'date',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function isSuperUser(): bool
    {
        return $this->super_user === 1;
    }

    // Get gender as string
    public function getGenderName(): string
    {
        return match ($this->gender) {
            self::GENDER_MALE => 'ကျား',
            self::GENDER_FEMALE => 'မိန်းမ',
            default => 'အခြား',
        };
    }

    // Get married status as string
    public function getMarriedStatusName(): string
    {
        return $this->married_status ? 'လက်ထပ်ထားသည်' : 'လူလွတ်';
    }

    public function getPositionName(): string
    {
        return $this->position ? $this->position->title : 'N/A';
    }
}
