<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Log\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->controller(ActivityLogController::class)->group(function () {
    Route::get('/logs', 'index')->name('logs');
    Route::post('/logs', 'search')->name('log.search');
    Route::get('/log/{id}', 'show')->name('log.show');
});
