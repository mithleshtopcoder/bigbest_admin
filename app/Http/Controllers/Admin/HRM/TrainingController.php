<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function programs()
    {
        return view('admin.hrm.training.programs');
    }

    public function calendar()
    {
        return view('admin.hrm.training.calendar');
    }

    public function enrollments()
    {
        return view('admin.hrm.training.enrollments');
    }
}

