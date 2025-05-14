<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Search\ElasticSearchSispController;
use App\Http\Controllers\Search\IMEIController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:imei'])->controller(IMEIController::class)->group(function () {
    Route::middleware(['auth'])->get('/imei', 'index')->name('imei.index');
    Route::middleware(['auth'])->post('/imei', 'search')->name('imei.search');
    Route::middleware(['auth'])->get('/imei/{imei}', 'show')->name('imei.show');
    Route::middleware(['auth'])->get('/imei/boletim/{bop}/{imei}', 'bop')->name('imei.bop');
    Route::middleware(['auth'])->get('/imei/gi2/{imei}', 'gi2')->name('imei.gi2');
    Route::get('/pesquisa/relato', [ElasticSearchSispController::class, 'index'])->name('pesquisa.relato');
    Route::post('/pesquisa/relato', [ElasticSearchSispController::class, 'elastic'])->name('pesquisa.relato.elastic');
    Route::get('/pesquisa/relato/show/', [ElasticSearchSispController::class, 'show'])->name('pesquisa.relato.show');
    Route::post('/pesquisa/relato/exportword', [ElasticSearchSispController::class, 'exportToWord'])->name('pesquisa.relato.export.word');

});
