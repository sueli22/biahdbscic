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
        'operation_year',
        'total_budget',
        'title_report',
        'status_report'
    ];

    protected $casts = [
        'total_investment' => 'decimal:2',
        'regional_budget' => 'decimal:2',
        'tender_price' => 'decimal:2',
        'operation_year' => 'integer',
    ];
}
