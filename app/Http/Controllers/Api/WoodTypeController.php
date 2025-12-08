<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WoodType;
use Illuminate\Http\JsonResponse;

class WoodTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $woodTypes = WoodType::orderBy('name')->get();

        return response()->json($woodTypes);
    }

    public function show(WoodType $woodType): JsonResponse
    {
        return response()->json($woodType);
    }

    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $woodType = WoodType::create($validated);

        return response()->json($woodType, 201);
    }

    public function update(WoodType $woodType): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $woodType->update($validated);

        return response()->json($woodType);
    }

    public function destroy(WoodType $woodType): JsonResponse
    {
        $woodType->delete();

        return response()->json(['message' => 'Wood type deleted']);
    }
}
