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
        'salary_month',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'payment_method',
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
