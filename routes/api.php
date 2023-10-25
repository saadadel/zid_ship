<?php

use App\Http\Controllers\ShipmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/shipment/create', [ShipmentController::class, 'store']);
Route::get('/shipment/{shipment}', [ShipmentController::class, 'show']);
Route::get('/shipment/{shipment}/status', [ShipmentController::class, 'showStatus']);