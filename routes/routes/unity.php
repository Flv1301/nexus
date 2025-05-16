<?php

use App\Http\Controllers\Registration\UnityController;
use Illuminate\Support\Facades\Route;

Route::middleware(['permission:unidade.setor.pesquisar'])->get('/unidade/setores', [UnityController::class, 'sectors'])->name('unity.sectors');

Route::middleware(['auth', 'permission:unidade'])->controller(UnityController::class)->group(
    function () {
        Route::get('/unidades', 'index')->name('unitys');
        Route::get('/unidade/', 'create')->name('unity.create');
        Route::get('/unidade/{id}', 'edit')->name('unity.edit');
        Route::post('/unidade/', 'store')->name('unity.store');
        Route::put('/unidade/{id}', 'update')->name('unity.update');
        Route::delete('/unidade/{id}', 'destroy')->name('unity.destroy');
    }
);
