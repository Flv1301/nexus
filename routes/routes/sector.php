<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Registration\SectorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:setor|setor.usuario.pesquisar'])->controller(SectorController::class)->group(
    function () {
        Route::get('/setor/usuarios', 'users')->name('unity.sector.users');
        Route::get('/setores', 'index')->name('sectors');
        Route::get('/setor/', 'create')->name('sector.create');
        Route::get('/setor/{id}', 'edit')->name('sector.edit');
        Route::post('/setor/', 'store')->name('sector.store');
        Route::put('/setor/{id}', 'update')->name('sector.update');
        Route::delete('/setor/{id}', 'destroy')->name('sector.destroy');
    }
);

