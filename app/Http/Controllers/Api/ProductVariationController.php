<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\JsonResponse;

class ProductVariationController extends Controller
{
    public function index(Product $product): JsonResponse
    {
        $variations = $product->variations()
            ->orderBy('name')
            ->get();

        return response()->json($variations);
    }

    public function update(Product $product, ProductVariation $variation): JsonResponse
    {
        if ($variation->product_id !== $product->id) {
            return response()->json(['error' => 'Variation does not belong to this product'], 403);
        }

        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|nullable|string',
        ]);

        $variation->update($validated);

        return response()->json($variation);
    }
}
