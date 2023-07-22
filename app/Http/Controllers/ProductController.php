<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;
use App\Models\ProductMaster;

class ProductController extends Controller
{
    public function create()
    {
        $products = ProductMaster::pluck('Product_Name', 'Product_ID')->toArray();
        $rates = ProductMaster::pluck('Rate', 'Product_ID')->toArray();
        $units = ProductMaster::pluck('Unit', 'Product_ID')->toArray();
        $invoiceDetails = InvoiceDetail::all();

        return view('product.create', compact('products', 'rates', 'units', 'invoiceDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'rate' => 'required|numeric',
            'unit' => 'required',
        ]);

        $product = new ProductMaster();
        $product->Product_Name = $request->product_name;
        $product->Rate = $request->rate;
        $product->Unit = $request->unit;
        $product->save();

        return redirect()->route('product.create')->with('success', 'Product added successfully.');
    }

    public function show($id)
    {
        $product = ProductMaster::find($id);
        return response()->json([
            'rate' => $product->Rate,
            'unit' => $product->Unit,
        ]);
    }

    public function edit($Product_ID)
    {
        $product = ProductMaster::findOrFail($Product_ID);
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $Product_ID)
    {
        $request->validate([
            'product_name' => 'required',
            'rate' => 'required|numeric',
            'unit' => 'required',
        ]);

        $product = ProductMaster::findOrFail($Product_ID);
        $product->Product_Name = $request->product_name;
        $product->Rate = $request->rate;
        $product->Unit = $request->unit;
        $product->save();

        return redirect()->route('product.create')->with('success', 'Product updated successfully.');
    }

    public function destroy($Product_ID)
    {
        $product = ProductMaster::findOrFail($Product_ID);
        $product->delete();
        return redirect()->route('product.create')->with('success', 'Product deleted successfully.');
    }
}
