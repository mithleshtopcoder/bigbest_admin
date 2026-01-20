<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        return view('store.inventory.index');
    }

    public function stockLog()
    {
        return view('store.inventory.stock-log');
    }

    public function alerts()
    {
        return view('store.inventory.alerts');
    }
}

