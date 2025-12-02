<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WcProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);

Route::get('/wc-products', [WcProductController::class, 'index']);
Route::get('/wc-products/{wcProduct}/variations', [WcProductController::class, 'variations']);
