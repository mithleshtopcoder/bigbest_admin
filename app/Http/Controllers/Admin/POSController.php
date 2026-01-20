<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function billing()
    {
        return view('admin.pos.billing');
    }

    public function holdResume()
    {
        return view('admin.pos.hold-resume');
    }

    public function barcodeBilling()
    {
        return view('admin.pos.barcode-billing');
    }

    public function orders()
    {
        return view('admin.pos.orders');
    }

    public function returnsRefunds()
    {
        return view('admin.pos.returns-refunds');
    }

    public function cashRegister()
    {
        return view('admin.pos.cash-register');
    }

    public function dayClose()
    {
        return view('admin.pos.day-close');
    }
}

