<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

######### AUTH ###########
Route::middleware('throttle:10,1')->post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});


