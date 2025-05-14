<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\RolePemission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:permissao'])->controller(PermissionController::class)->group(function () {
    Route::get('/permissoes', 'index')->name('permissions');
    Route::get('/permissao', 'create')->name('permission.create');
    Route::post('/permissao', 'store')->name('permission.store');
    Route::delete('/permissao/{id}', 'destroy')->name('permission.destroy');
});
