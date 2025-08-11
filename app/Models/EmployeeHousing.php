<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHousing extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECT = 'reject';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'family_member',
        'house_hold_img',
        'description',
        'township',
        'status',
        'submit_date',
        'approved_date',
    ];

    // Relationship: EmployeeHousing belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
