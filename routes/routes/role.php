<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\RolePemission\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:funcao'])->controller(RoleController::class)->group(function () {
    Route::get('/funcoes', 'index')->name('roles');
    Route::get('/funcao/ler/{id}', 'show')->name('role.show');
    Route::get('/funcao', 'create')->name('role.create');
    Route::post('/funcao', 'store')->name('role.store');
    Route::get('/funcao/{id}', 'edit')->name('role.edit');
    Route::put('/funcao/{id}', 'update')->name('role.update');
    Route::delete('/funcao/{id}', 'destroy')->name('role.destroy');
});
