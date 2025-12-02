<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WcProduct;
use Illuminate\Http\JsonResponse;

class WcProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = WcProduct::with('images')
            ->withCount('variations')
            ->get();

        return response()->json($products);
    }

    public function variations(WcProduct $wcProduct): JsonResponse
    {
        $variations = $wcProduct->variations()
            ->orderBy('name')
            ->get();

        return response()->json($variations);
    }
}
