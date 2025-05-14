<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Person\PersonController;
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

});


