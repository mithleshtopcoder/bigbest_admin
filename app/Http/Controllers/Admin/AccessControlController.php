<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccessControlController extends Controller
{
    public function employees()
    {
        return view('admin.access-control.employees');
    }

    public function roles()
    {
        return view('admin.access-control.roles');
    }

    public function permissions()
    {
        return view('admin.access-control.permissions');
    }

    public function storeMapping()
    {
        return view('admin.access-control.store-mapping');
    }

    public function salesReport()
    {
        return view('admin.access-control.sales-report');
    }
}

