<?php

use App\Http\Controllers\Log\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->controller(ActivityLogController::class)->group(function () {
    Route::get('/logs', 'index')->name('logs');
    Route::post('/logs', 'search')->name('log.search');
    Route::get('/log/{id}', 'show')->name('log.show');
});
