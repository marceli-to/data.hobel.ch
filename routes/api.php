<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('web')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::post('/products/bulk-edit', [ProductController::class, 'bulkEdit']);

    Route::get('/products/{product}/variations', [ProductVariationController::class, 'index']);
    Route::put('/products/{product}/variations/{variation}', [ProductVariationController::class, 'update']);

    Route::get('/products/{product}/remarks', [ProductController::class, 'remarks']);
    Route::post('/products/{product}/remarks', [ProductController::class, 'storeRemark']);
    Route::delete('/products/{product}/remarks/{remark}', [ProductController::class, 'destroyRemark']);

    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/categories/{category}/tags', [\App\Http\Controllers\Api\CategoryController::class, 'tags']);
});
