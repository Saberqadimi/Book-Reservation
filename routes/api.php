<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
#auth
Route::controller(\App\Http\Controllers\Auth\AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});
#reservation
Route::middleware('auth:sanctum')->controller(\App\Http\Controllers\ReservationController::class)
    ->group(function () {
        Route::get('reservations', 'index');
        Route::post('reservations', 'store');
        Route::post('reservations/{reservation}/complete', 'complete');
    });
