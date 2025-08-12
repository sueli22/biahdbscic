<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySalary extends Model
{
    use HasFactory;

    protected $table = 'pay_salaries';

    protected $fillable = [
        'user_id',
        'pay_date',
        'salary_month',
        'basic_salary',
        'allowances',
        'bonus',
        'deductions',
        // 'net_salary' is virtual/computed by DB, so no need to fill
        'payment_method',
        'payment_status',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'pay_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Relationship: PaySalary belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
