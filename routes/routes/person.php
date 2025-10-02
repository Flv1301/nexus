<?php

use App\Http\Controllers\Person\PersonController;

use App\Http\Controllers\Pge\PgeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:pessoa'])->controller(PersonController::class)->group(function () {
    Route::get('/pessoas', 'index')->name('persons');
    Route::get('/pessoa', 'create')->name('person.create');
    Route::get('/pessoa/buscar/', 'search')->name('person.register.search');
    Route::get('/pessoa/dados/{id}', 'show')->name('person.show');
    Route::get('/pessoa/{id}', 'edit')->name('person.edit');
    Route::post('/pessoa/', 'store')->name('person.store');
    Route::post('/pessoa/anexo/{id}', 'storeAttachment')->name('person.attachment');
    Route::put('/pessoa/{id}', 'update')->name('person.update');
    Route::delete('/pessoa/{id}', 'destroy')->name('person.destroy');
    Route::delete('/pessoa/imagem/{personId}/{imageId}', 'removeImage')->name('person.remove.image');

    // Rota específica para servir documentos com headers apropriados
    Route::get('/pessoa/documento/{personId}/{docId}', 'serveDocument')
        ->name('person.serve.document')
        ->middleware('web');
});


// Rota para consulta ao PGE via API
Route::middleware(['auth', 'permission:pessoa'])->controller(PgeController::class)->group(function () {
    Route::get('/pge/detran', 'consult')->name('pge.detran');
    Route::get('/pge/adepara', 'consult')->name('pge.adepara');
    Route::get('/pge/semas', 'consult')->name('pge.semas');
    Route::get('/pge/jucepa', 'consult')->name('pge.jucepa');
});

