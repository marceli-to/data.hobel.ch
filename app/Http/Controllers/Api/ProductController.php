<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->orderBy('title')
            ->get();

        return response()->json($products);
    }
}
