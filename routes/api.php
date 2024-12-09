<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'store']);

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/category', [CategoriesController::class, 'store']);
    Route::get('/category', [CategoriesController::class, 'index']);
    Route::get('/select', [CategoriesController::class, 'select']);
    
    Route::get('/category/{id}', [CategoriesController::class, 'show']);
    Route::put('/category/{id}', [CategoriesController::class, 'update']);
    Route::delete('/category/{id}', [CategoriesController::class, 'destroy']);


    Route::post('/product', [ProductsController::class, 'store']);
    Route::get('/product', [ProductsController::class, 'index']);
    Route::get('/product/{id}', [ProductsController::class, 'show']);
    Route::put('/product/{id}', [ProductsController::class, 'update']);
    Route::delete('/product/{id}', [ProductsController::class, 'destroy']);
    Route::post('/stok/{id}', [ProductsController::class, 'updateStok']);
    Route::post('/sold/{id}', [ProductsController::class, 'soldStok']);


    Route::get('/graphic', [ProductsController::class, 'graphic']);


});

