<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

test('can create a tag', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create([
        'category_id' => $category->id,
        'name' => 'Test Tag',
        'slug' => 'test-tag',
    ]);

    expect($tag->name)->toBe('Test Tag');
    expect($tag->slug)->toBe('test-tag');
    expect($tag->category_id)->toBe($category->id);
});

test('tag belongs to a category', function () {
    $category = Category::factory()->create(['name' => 'Parent Category']);
    $tag = Tag::factory()->create(['category_id' => $category->id]);

    expect($tag->category)->toBeInstanceOf(Category::class);
    expect($tag->category->name)->toBe('Parent Category');
});

test('tag belongs to many products', function () {
    $tag = Tag::factory()->create();
    $products = Product::factory()->count(3)->create();

    $tag->products()->attach($products);

    expect($tag->products)->toHaveCount(3);
    expect($tag->products->first())->toBeInstanceOf(Product::class);
});

test('tag slug is unique', function () {
    Tag::factory()->create([
        'name' => 'Unique Tag',
        'slug' => 'unique-tag',
    ]);

    expect(fn () => Tag::factory()->create([
        'name' => 'Another Tag',
        'slug' => 'unique-tag',
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

test('deleting a category deletes its tags', function () {
    $category = Category::factory()->create();
    $tag = Tag::factory()->create(['category_id' => $category->id]);

    $tagId = $tag->id;
    $category->delete();

    expect(Tag::find($tagId))->toBeNull();
});
