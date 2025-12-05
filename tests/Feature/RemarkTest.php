<?php

use App\Models\Product;
use App\Models\Remark;
use App\Models\User;

test('can create a remark', function () {
    $product = Product::factory()->create();
    $remark = Remark::factory()->create([
        'product_id' => $product->id,
        'description' => 'Test remark',
    ]);

    expect($remark->description)->toBe('Test remark');
    expect($remark->product_id)->toBe($product->id);
});

test('remark belongs to a product', function () {
    $product = Product::factory()->create(['name' => 'Test Product']);
    $remark = Remark::factory()->create(['product_id' => $product->id]);

    expect($remark->product)->toBeInstanceOf(Product::class);
    expect($remark->product->name)->toBe('Test Product');
});

test('product has many remarks', function () {
    $product = Product::factory()->create();
    $remarks = Remark::factory()->count(3)->create([
        'product_id' => $product->id,
    ]);

    expect($product->remarks)->toHaveCount(3);
    expect($product->remarks->first())->toBeInstanceOf(Remark::class);
});

test('remark has nullable done_at field', function () {
    $product = Product::factory()->create();
    $remark = Remark::factory()->create([
        'product_id' => $product->id,
        'done_at' => null,
    ]);

    expect($remark->done_at)->toBeNull();

    $remarkWithDate = Remark::factory()->create([
        'product_id' => $product->id,
        'done_at' => now(),
    ]);

    expect($remarkWithDate->done_at)->not->toBeNull();
});

test('remark belongs to a user', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $remark = Remark::factory()->create(['user_id' => $user->id]);

    expect($remark->user)->toBeInstanceOf(User::class);
    expect($remark->user->name)->toBe('John Doe');
});

test('user has many remarks', function () {
    $user = User::factory()->create();
    $remarks = Remark::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->remarks)->toHaveCount(3);
    expect($user->remarks->first())->toBeInstanceOf(Remark::class);
});

test('deleting a product deletes its remarks', function () {
    $product = Product::factory()->create();
    $remark = Remark::factory()->create(['product_id' => $product->id]);

    $remarkId = $remark->id;
    $product->delete();

    expect(Remark::find($remarkId))->toBeNull();
});

test('deleting a user sets remarks user_id to null', function () {
    $user = User::factory()->create();
    $remark = Remark::factory()->create(['user_id' => $user->id]);

    $remarkId = $remark->id;
    $user->delete();

    $remark = Remark::find($remarkId);
    expect($remark)->not->toBeNull();
    expect($remark->user_id)->toBeNull();
});

test('bulk edit can add remarks to multiple products', function () {
    $products = Product::factory()->count(3)->create();
    $productIds = $products->pluck('id')->toArray();

    $response = $this->postJson('/api/products/bulk-edit', [
        'product_ids' => $productIds,
        'action' => 'remarks',
        'remarks' => 'Bulk remark test',
    ]);

    $response->assertSuccessful();

    foreach ($products as $product) {
        expect($product->fresh()->remarks)->toHaveCount(1);
        expect($product->fresh()->remarks->first()->description)->toBe('Bulk remark test');
        expect($product->fresh()->remarks->first()->done_at)->not->toBeNull();
    }
});
