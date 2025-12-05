<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

test('can create a category', function () {
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'slug' => 'test-category',
    ]);

    expect($category->name)->toBe('Test Category');
    expect($category->slug)->toBe('test-category');
});

test('category has many tags', function () {
    $category = Category::factory()->create();
    $tags = Tag::factory()->count(3)->create([
        'category_id' => $category->id,
    ]);

    expect($category->tags)->toHaveCount(3);
    expect($category->tags->first())->toBeInstanceOf(Tag::class);
});

test('category belongs to many products', function () {
    $category = Category::factory()->create();
    $products = Product::factory()->count(2)->create();

    $category->products()->attach($products);

    expect($category->products)->toHaveCount(2);
    expect($category->products->first())->toBeInstanceOf(Product::class);
});

test('category slug is unique', function () {
    Category::factory()->create([
        'name' => 'Unique Category',
        'slug' => 'unique-category',
    ]);

    expect(fn () => Category::factory()->create([
        'name' => 'Another Category',
        'slug' => 'unique-category',
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});
