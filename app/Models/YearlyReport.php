<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyReport extends Model
{
    use HasFactory;

    // Table name (optional if it matches the plural of the model)
    protected $table = 'yearly_reports';

    // Mass assignable fields
    protected $fillable = [
        'from',
        'to',
        'name',
        'location',
        'start_month',
        'end_month',
        'department',
        'total_investment',
        'operation_year',
        'regional_budget',
        'tender_price',
    ];

    // Optional: Cast monetary fields to decimal
    protected $casts = [
        'total_investment' => 'decimal:2',
        'regional_budget' => 'decimal:2',
        'tender_price' => 'decimal:2',
        'operation_year' => 'integer',
    ];
}
