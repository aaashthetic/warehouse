<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function getProducts()
    {
        return response()->json(Product::all());
    }

    public function addProduct(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string',
            'product_name' => 'required|string'
        ]);

        $product = Product::create([
            'sku'          => $validated['sku'],
            'product_name' => $validated['product_name']
        ]);

        return response()->json([
            'message' => 'Product added successfully',
            'data'    => $product
        ], 201);
    }
}