<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with(['images', 'productCategories', 'productTags'])
            ->withCount(['variations', 'remarks'])
            ->get();

        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load(['images', 'productCategories', 'productTags']);
        
        return response()->json($product);
    }

    public function update(Product $product): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
            'label' => 'sometimes|nullable|string|max:255',
            'sku' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    public function remarks(Product $product): JsonResponse
    {
        $remarks = $product->remarks()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($remarks);
    }

    public function storeRemark(Product $product): JsonResponse
    {
        $validated = request()->validate([
            'description' => 'required|string',
        ]);

        $remark = $product->remarks()->create([
            'user_id' => auth()->id(),
            'description' => $validated['description'],
        ]);

        return response()->json($remark->load('user'), 201);
    }

    public function destroyRemark(Product $product, \App\Models\Remark $remark): JsonResponse
    {
        if ($remark->product_id !== $product->id) {
            return response()->json(['error' => 'Remark does not belong to this product'], 403);
        }

        $remark->delete();

        return response()->json(['message' => 'Remark deleted']);
    }

    public function bulkEdit(): JsonResponse
    {
        $validated = request()->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'action' => 'required|in:remarks,category_tags,set_type,mark_done,delete',
            'remarks' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
            'type' => 'nullable|in:configurable,simple,variations',
        ]);

        $products = Product::query()->whereIn('id', $validated['product_ids'])->get();

        if ($validated['action'] === 'remarks') {
            foreach ($products as $product) {
                $product->remarks()->create([
                    'user_id' => auth()->id(),
                    'description' => $validated['remarks'],
                    'done_at' => now(),
                ]);
            }
        } elseif ($validated['action'] === 'category_tags') {
            foreach ($products as $product) {
                // Attach category
                if (isset($validated['category_id'])) {
                    $product->productCategories()->syncWithoutDetaching([$validated['category_id']]);
                }

                // Attach tags
                if (isset($validated['tag_ids']) && ! empty($validated['tag_ids'])) {
                    $product->productTags()->syncWithoutDetaching($validated['tag_ids']);
                }
            }
        } elseif ($validated['action'] === 'set_type') {
            Product::query()->whereIn('id', $validated['product_ids'])->update(['type' => $validated['type']]);
        } elseif ($validated['action'] === 'mark_done') {
            Product::query()->whereIn('id', $validated['product_ids'])->update(['done_at' => now()]);
        } elseif ($validated['action'] === 'delete') {
            Product::query()->whereIn('id', $validated['product_ids'])->delete();
        }

        return response()->json(['message' => 'Products updated successfully']);
    }
}
