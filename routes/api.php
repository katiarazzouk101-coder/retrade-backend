<?php

use App\Http\Controllers\API\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\CartController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::middleware('auth:sanctum')->post('logout', 'logout');
});


Route::middleware('auth.api:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth.api:sanctum');

Route::middleware('auth.api:sanctum')->group(function () {
    Route::post('/products/{id}/like', [ProductController::class, 'like']);
    Route::delete('/products/{id}/like', [ProductController::class, 'unlike']);
});

Route::middleware('auth.api:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'viewCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart']);
});
