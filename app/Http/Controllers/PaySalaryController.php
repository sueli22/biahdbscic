<?php

namespace App\Http\Controllers;

use App\Models\PaySalary;
use App\Models\User;
use Illuminate\Http\Request;

class PaySalaryController extends Controller
{
    public function index()
    {
        // Load users for dropdown
        $users = User::select('id', 'name')->get();
        $paySalaries = PaySalary::with('user')->latest()->get();
        return view('admin.payroll.index', compact('users', 'paySalaries'));
    }

}
