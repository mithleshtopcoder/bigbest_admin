<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('store.expenses.index');
    }

    public function create()
    {
        return view('store.expenses.create');
    }

    public function store(Request $request)
    {
        // Implementation for storing expense
        return redirect()->route('store.expenses.index')
            ->with('success', 'Expense added successfully');
    }

    public function reports()
    {
        return view('store.expenses.reports');
    }
}

