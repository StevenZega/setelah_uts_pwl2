<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::prefix('products')->group(function() {
    Route::get('/lihat', [ProductController::class, 'lihat']);
    Route::get('/lihat_id/{id}', [ProductController::class, 'lihat_by_id']);
});


Route::apiResource('users', UserController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', function () {
    return response()->json(['message' => 'API is working']);
});