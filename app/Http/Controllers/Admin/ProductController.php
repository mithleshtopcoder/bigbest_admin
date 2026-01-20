<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.manage-product.index');
    }
    
    public function create()
    {
        return view('admin.manage-product.create');
    }
    
    public function edit($id)
    {
        return view('admin.manage-product.edit', compact('id'));
    }

    public function store(Request $request)
    {
        // Static design only
        return redirect()->route('admin.products.manage-product.index');
    }

    public function update(Request $request, $id)
    {
        // Static design only
        return redirect()->route('admin.products.manage-product.index');
    }

    public function destroy($id)
    {
        // Static design only
        return redirect()->route('admin.products.manage-product.index');
    }

    public function variants()
    {
        return view('admin.products.variants');
    }

    public function unitsAttributes()
    {
        return view('admin.products.units-attributes');
    }

    public function priceManagement()
    {
        return view('admin.products.price-management');
    }

    public function visibility()
    {
        return view('admin.products.visibility');
    }
}
