<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('tags')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function show(Category $category): JsonResponse
    {
        $category->load('tags');

        return response()->json($category);
    }

    public function store(): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($category, 201);
    }

    public function update(Category $category): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }

    public function tags(Category $category): JsonResponse
    {
        $tags = $category->tags()
            ->orderBy('name')
            ->get();

        return response()->json($tags);
    }

    public function storeTag(Category $category): JsonResponse
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = $category->tags()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($tag, 201);
    }

    public function updateTag(Category $category, Tag $tag): JsonResponse
    {
        if ($tag->category_id !== $category->id) {
            return response()->json(['error' => 'Tag does not belong to this category'], 403);
        }

        $validated = request()->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->update($validated);

        return response()->json($tag);
    }

    public function destroyTag(Category $category, Tag $tag): JsonResponse
    {
        if ($tag->category_id !== $category->id) {
            return response()->json(['error' => 'Tag does not belong to this category'], 403);
        }

        $tag->delete();

        return response()->json(['message' => 'Tag deleted']);
    }
}
