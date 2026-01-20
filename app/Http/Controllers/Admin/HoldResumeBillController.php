<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HoldResumeBillController extends Controller
{
    /**
     * Display a listing of held bills.
     */
    public function index()
    {
        return view('hold-resume-bill.index');
    }
}

