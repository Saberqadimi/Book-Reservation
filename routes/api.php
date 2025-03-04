<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/me', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(\App\Http\Controllers\Auth\AuthController::class)->group(function () {
   Route::post('/login', 'login');
   Route::post('/register', 'register');
});
