<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('store.orders.index');
    }

    public function view($id)
    {
        return view('store.orders.view', compact('id'));
    }

    public function accept($id)
    {
        // Implementation for accepting order
        return redirect()->route('store.orders.index')
            ->with('success', 'Order accepted successfully');
    }

    public function reject($id)
    {
        // Implementation for rejecting order
        return redirect()->route('store.orders.index')
            ->with('success', 'Order rejected');
    }

    public function track()
    {
        return view('store.orders.track');
    }

    public function history()
    {
        return view('store.orders.history');
    }
}

