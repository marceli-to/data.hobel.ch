<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

test('product can have multiple categories', function () {
    $product = Product::factory()->create();
    $categories = Category::factory()->count(3)->create();

    $product->productCategories()->attach($categories);

    expect($product->productCategories)->toHaveCount(3);
    expect($product->productCategories->first())->toBeInstanceOf(Category::class);
});

test('product can have multiple tags', function () {
    $product = Product::factory()->create();
    $tags = Tag::factory()->count(4)->create();

    $product->productTags()->attach($tags);

    expect($product->productTags)->toHaveCount(4);
    expect($product->productTags->first())->toBeInstanceOf(Tag::class);
});

test('product can be associated with categories and tags', function () {
    $category = Category::factory()->create(['name' => 'Tische']);
    $tag = Tag::factory()->create([
        'category_id' => $category->id,
        'name' => 'Esstische',
    ]);
    $product = Product::factory()->create(['name' => 'Oak Dining Table']);

    $product->productCategories()->attach($category);
    $product->productTags()->attach($tag);

    expect($product->productCategories)->toHaveCount(1);
    expect($product->productTags)->toHaveCount(1);
    expect($product->productCategories->first()->name)->toBe('Tische');
    expect($product->productTags->first()->name)->toBe('Esstische');
});

test('multiple products can share the same category', function () {
    $category = Category::factory()->create();
    $products = Product::factory()->count(5)->create();

    foreach ($products as $product) {
        $product->productCategories()->attach($category);
    }

    expect($category->products)->toHaveCount(5);
});

test('multiple products can share the same tag', function () {
    $tag = Tag::factory()->create();
    $products = Product::factory()->count(5)->create();

    foreach ($products as $product) {
        $product->productTags()->attach($tag);
    }

    expect($tag->products)->toHaveCount(5);
});

test('deleting a product removes pivot relationships', function () {
    $product = Product::factory()->create();
    $category = Category::factory()->create();
    $tag = Tag::factory()->create();

    $product->productCategories()->attach($category);
    $product->productTags()->attach($tag);

    $productId = $product->id;
    $product->delete();

    expect(Product::find($productId))->toBeNull();
    expect($category->fresh()->products)->toHaveCount(0);
    expect($tag->fresh()->products)->toHaveCount(0);
});
