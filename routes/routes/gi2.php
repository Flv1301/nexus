<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Gi2\Gi2Controller;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:gi2'])->controller(Gi2Controller::class)->group(function () {
    Route::get('/gi2', 'index')->name('gi2');
    Route::get('/gi2/create', 'create')->name('gi2.create');
    Route::post('/gi2/save', 'save')->name('gi2.save');
    Route::get('/gi2/{id}', 'show')->name('gi2.show');
    Route::post('/gi2', 'search')->name('gi2.search');
    Route::get('/gi2/intersecao/imei', 'intersection')->name('gi2.intersection');
    Route::Post('/gi2/intersecao/imei', 'filterIntersection')->name('gi2.intersection.filter');

});
