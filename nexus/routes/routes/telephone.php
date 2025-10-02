<?php

use App\Http\Controllers\Search\TelephoneSearchController;
use Illuminate\Support\Facades\Route;

/** PESQUISAS */
Route::middleware(['auth'])->controller(TelephoneSearchController::class)->group(function () {
    Route::middleware(['auth'])->get('/pesquisar/telefone', 'index')->name('telephone.search.index');
    Route::middleware(['auth'])->get('/pesquisar/telefone/ddd/number', 'search' )->name('telephone.search');
    Route::middleware(['auth'])->get('/pesquisar/telefone/{base}/{id}', 'show')->name('telephone.search.show');
});

