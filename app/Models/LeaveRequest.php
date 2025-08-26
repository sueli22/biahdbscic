<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECT = 'reject';

    protected $fillable = [
        'req_type',
        'user_id',
        'leave_type_id',
        'from_date',
        'duration',
        'to_date',
        'description',
        'img',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Leave type of the request
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
