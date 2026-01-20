<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppControlController extends Controller
{
    public function banners()
    {
        return view('admin.app-control.banners');
    }

    public function featuredProducts()
    {
        return view('admin.app-control.featured-products');
    }

    public function categories()
    {
        return view('admin.app-control.categories');
    }

    public function contentPages()
    {
        return view('admin.app-control.content-pages');
    }

    public function storeAvailability()
    {
        return view('admin.app-control.store-availability');
    }

    public function orderRadius()
    {
        return view('admin.app-control.order-radius');
    }
}

