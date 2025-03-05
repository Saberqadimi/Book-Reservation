<?php

use App\Http\Controllers\BookController;
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

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    #reservation
    Route::controller(\App\Http\Controllers\ReservationController::class)
        ->group(function () {
            Route::get('reservations', 'index');
            Route::post('reservations', 'store');
            Route::post('reservations/{reservation}/complete', 'complete');
        });
    #book
    Route::controller(\App\Http\Controllers\BookController::class)->group(function () {
        Route::get('books', 'index');
        Route::post('books', 'newOrUpdate');
        Route::delete('books/{book}', 'destroy');
    });
    #bookCopies
    Route::apiResource('book-copies', \App\Http\Controllers\BookCopyController::class)->only(['index', 'store', 'destroy', 'update']);
});
