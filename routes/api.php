<?php

use App\Http\Controllers\Api\CadCivilProdepaApiController;
use App\Http\Controllers\Api\SispApiController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

######### AUTH ###########
Route::middleware('throttle:10,1')->post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('/cadastroscivis', [CadCivilProdepaApiController::class, 'search']);
    Route::get('/cadastrocivil/{id}', [CadCivilProdepaApiController::class, 'show']);
    Route::get('/sisp', [SispApiController::class, 'search'])->name('sisp.search');
    Route::get('/sisp/{id}', [SispApiController::class, 'show'])->name('sisp.show');
});


