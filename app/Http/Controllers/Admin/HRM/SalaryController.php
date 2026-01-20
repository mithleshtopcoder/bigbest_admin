<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function structure()
    {
        return view('admin.hrm.salary.structure');
    }

    public function payroll()
    {
        return view('admin.hrm.salary.payroll');
    }

    public function processPayroll(Request $request)
    {
        // Implementation for processing payroll
        return redirect()->route('admin.hrm.salary.payroll')
            ->with('success', 'Payroll processed successfully');
    }

    public function payslips()
    {
        return view('admin.hrm.salary.payslips');
    }
}

