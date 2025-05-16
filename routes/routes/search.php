<?php

use App\Http\Controllers\Search\PersonSearchController;
use App\Http\Controllers\Search\VehicleSearchController;
use App\Http\Controllers\VCard\VCardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:veiculo'])->get('/veiculo', fn() => view('search.vehicle.index'))->name('vehicle.index');
Route::middleware(['auth', 'permission:vcard'])->get('/vcard', [VCardController::class, 'index'])->name('vcard.index');
Route::middleware(['auth', 'permission:vcard'])->post('/vcard', [VCardController::class, 'search'])->name('vcard.search.phone');

/** BUSCA PESSOA COMPLETA */
Route::middleware(['auth', 'permission:pesquisa_pessoa_completa'])->controller(PersonSearchController::class)->group(function () {
    Route::middleware(['auth'])->get('/pesquisa/pessoa', 'index')->name('person.search.index');
    Route::middleware(['auth'])->get('/pesquisa/pessoa/{base}/{id}', 'show')->name('person.search.show');
    Route::middleware(['auth'])->post('/pesquisa/pessoa', 'search')->name('person.search');
});

/** VEICULOS */
Route::middleware(['auth'])->controller(VehicleSearchController::class)->group( function (){
   Route::get('/pesquisa/veiculo/{plate?}', 'search')->name('search.vehicle.plate');
});
