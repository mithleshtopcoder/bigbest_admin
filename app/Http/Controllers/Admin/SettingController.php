<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function business()
    {
        return view('admin.settings.business');
    }

    public function store()
    {
        return view('admin.settings.store');
    }

    public function pos()
    {
        return view('admin.settings.pos');
    }

    public function paymentMethods()
    {
        return view('admin.settings.payment-methods');
    }

    public function tax()
    {
        return view('admin.settings.tax');
    }

    public function invoiceTemplates()
    {
        return view('admin.settings.invoice-templates');
    }

    public function receiptTemplates()
    {
        return view('admin.settings.receipt-templates');
    }
}

