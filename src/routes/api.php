<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\OrderController;

Route::get('/merchandises', [MerchandiseController::class, 'index']);
Route::get('/toppings', [ToppingController::class, 'index']);
Route::get('/orders', [OrderController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
