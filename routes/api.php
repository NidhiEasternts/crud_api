<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'v1'], function() {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('product/{product}', [ProductController::class, 'update']);
        Route::resource('product', ProductController::class);
    });
});
