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
            'label' => 'sometimes|nullable|string|max:255',
            'sku' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|nullable|string',
            'stock' => 'sometimes|nullable|integer',
        ]);

        $variation->update($validated);

        return response()->json($variation);
    }

    public function bulkUpdate(Product $product): JsonResponse
    {
        $validated = request()->validate([
            'variation_ids' => 'required|array',
            'variation_ids.*' => 'integer',
            'updates' => 'required|array',
            'updates.name' => 'sometimes|string|max:255',
            'updates.label' => 'sometimes|string|max:255',
            'updates.sku' => 'sometimes|string|max:255',
            'updates.price' => 'sometimes|string',
            'updates.stock' => 'sometimes|nullable|integer',
        ]);

        if (!empty($validated['updates'])) {
            $product->variations()
                ->whereIn('id', $validated['variation_ids'])
                ->update($validated['updates']);
        }

        return response()->json(['message' => 'Variations updated']);
    }

    public function destroy(Product $product, ProductVariation $variation): JsonResponse
    {
        if ($variation->product_id !== $product->id) {
            return response()->json(['error' => 'Variation does not belong to this product'], 403);
        }

        $variation->delete();

        return response()->json(['message' => 'Variation deleted']);
    }
}
