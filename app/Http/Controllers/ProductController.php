<?php

namespace App\Http\Controllers;

use App\Models\Product;
use domain\Facades\ProductFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    protected $product;

    public function index()
    {
        $products = ProductFacade::all();
        return view('dashboard', compact('products'));
    }

    public function  insert(Request $request)
    {
        ProductFacade::store($request->toArray());
        return redirect('dashboard')->with('status', "Product Added Successfully");
    }

    public function page()
    {
        return view('layouts.admin.add-product');
    }

    public function status($id)
    {
        ProductFacade::status($id);
        return redirect('dashboard')->with('status', "Product Updated Successfully");
    }

    public function delete($id)
    {
        ProductFacade::destroy($id);
        return redirect('dashboard')->with('status', "Product Deleted Successfully");
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('layouts.admin.update-product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        ProductFacade::update($request, $id);
        return redirect('dashboard')->with('status', "Product Updated Successfully");
    }
}
