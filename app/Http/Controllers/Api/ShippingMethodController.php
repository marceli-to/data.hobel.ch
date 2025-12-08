<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\JsonResponse;

class ShippingMethodController extends Controller
{
    public function index(): JsonResponse
    {
        $shippingMethods = ShippingMethod::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return response()->json($shippingMethods);
    }

    public function show(ShippingMethod $shippingMethod): JsonResponse
    {
        return response()->json($shippingMethod);
    }

    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        $shippingMethod = ShippingMethod::create($validated);

        return response()->json($shippingMethod, 201);
    }

    public function update(ShippingMethod $shippingMethod): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'nullable|numeric|min:0',
        ]);

        $shippingMethod->update($validated);

        return response()->json($shippingMethod);
    }

    public function destroy(ShippingMethod $shippingMethod): JsonResponse
    {
        // Detach all products first
        $shippingMethod->products()->detach();

        $shippingMethod->delete();

        return response()->json(['message' => 'Shipping method deleted']);
    }
}
