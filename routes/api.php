<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Client\Pool;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DeliveryManController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/delivery-men/{id}/respond-to-order', [DeliveryManController::class, 'respondToOrder']);
});
