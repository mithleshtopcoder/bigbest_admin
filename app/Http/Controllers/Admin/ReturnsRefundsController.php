<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnsRefundsController extends Controller
{
    /**
     * Display a listing of returns and refunds.
     */
    public function index()
    {
        return view('returns-refunds.index');
    }
}

