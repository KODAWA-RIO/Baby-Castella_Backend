<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MerchandiseController;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\OrderController;

Route::get('/merchandises', [MerchandiseController::class, 'index']);
Route::post('/merchandises/store', [MerchandiseController::class, 'store']);
Route::get('/merchandises/{id}', [MerchandiseController::class, 'show']);
Route::put('/merchandises/{id}', [MerchandiseController::class, 'update']);
Route::delete('/merchandises/{id}', [MerchandiseController::class, 'destroy']);

Route::get('/toppings', [ToppingController::class, 'index']);
Route::post('/toppings/store', [ToppingController::class, 'store']);
Route::get('/toppings/{id}', [ToppingController::class, 'show']);
Route::put('/toppings/{id}', [ToppingController::class, 'update']);
Route::delete('/toppings/{id}', [ToppingController::class, 'destroy']);

Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// 指定された situation に基づく注文データを取得
Route::get('/orders/situation/{situation}', [OrderController::class, 'ordersBySituation']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
