<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\API\SaleController;



Route::post('/login', [UserController::class, 'login']);
Route::get('/products/search', [ProductController::class, 'search']);
Route::get('/products/find', [ProductController::class, 'find']);


Route::middleware(['auth:sanctum'])->group(function()
{
Route::post('/sales', [SaleController::class, 'store']);

});
