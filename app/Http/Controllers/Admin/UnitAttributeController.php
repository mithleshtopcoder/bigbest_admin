<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UnitAttributeController extends Controller
{
    /**
     * Display a listing of unit attributes.
     */
    public function index()
    {
        return view('manage-product.unit-attribute.index');
    }

    /**
     * Show the form for creating a new unit attribute.
     */
    public function create()
    {
        return view('manage-product.unit-attribute.create');
    }
}

