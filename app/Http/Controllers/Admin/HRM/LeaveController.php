<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        return view('admin.hrm.leave.index');
    }

    public function apply()
    {
        return view('admin.hrm.leave.apply');
    }

    public function storeApplication(Request $request)
    {
        // Implementation for storing leave application
        return redirect()->route('admin.hrm.leave.applications')
            ->with('success', 'Leave application submitted successfully');
    }

    public function applications()
    {
        return view('admin.hrm.leave.applications');
    }

    public function approve($id)
    {
        // Implementation for approving leave
        return redirect()->route('admin.hrm.leave.applications')
            ->with('success', 'Leave approved successfully');
    }

    public function reject($id)
    {
        // Implementation for rejecting leave
        return redirect()->route('admin.hrm.leave.applications')
            ->with('success', 'Leave rejected');
    }
}

