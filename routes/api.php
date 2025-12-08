<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariationController;
use App\Http\Controllers\Api\ShippingMethodController;
use App\Http\Controllers\Api\WoodTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('web')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products/bulk-edit', [ProductController::class, 'bulkEdit']);

    Route::get('/products/{product}/variations', [ProductVariationController::class, 'index']);
    Route::put('/products/{product}/variations/{variation}', [ProductVariationController::class, 'update']);
    Route::delete('/products/{product}/variations/{variation}', [ProductVariationController::class, 'destroy']);
    Route::post('/products/{product}/variations/bulk-update', [ProductVariationController::class, 'bulkUpdate']);

    Route::get('/products/{product}/remarks', [ProductController::class, 'remarks']);
    Route::post('/products/{product}/remarks', [ProductController::class, 'storeRemark']);
    Route::delete('/products/{product}/remarks/{remark}', [ProductController::class, 'destroyRemark']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/categories/{category}/tags', [CategoryController::class, 'tags']);
    Route::post('/categories/{category}/tags', [CategoryController::class, 'storeTag']);
    Route::put('/categories/{category}/tags/{tag}', [CategoryController::class, 'updateTag']);
    Route::delete('/categories/{category}/tags/{tag}', [CategoryController::class, 'destroyTag']);

    Route::get('/wood-types', [WoodTypeController::class, 'index']);
    Route::get('/wood-types/{woodType}', [WoodTypeController::class, 'show']);
    Route::post('/wood-types', [WoodTypeController::class, 'store']);
    Route::put('/wood-types/{woodType}', [WoodTypeController::class, 'update']);
    Route::delete('/wood-types/{woodType}', [WoodTypeController::class, 'destroy']);

    Route::get('/shipping-methods', [ShippingMethodController::class, 'index']);
    Route::get('/shipping-methods/{shippingMethod}', [ShippingMethodController::class, 'show']);
    Route::post('/shipping-methods', [ShippingMethodController::class, 'store']);
    Route::put('/shipping-methods/{shippingMethod}', [ShippingMethodController::class, 'update']);
    Route::delete('/shipping-methods/{shippingMethod}', [ShippingMethodController::class, 'destroy']);
});
