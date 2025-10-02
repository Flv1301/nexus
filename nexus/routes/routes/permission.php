<?php

use App\Http\Controllers\RolePemission\PermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:permissao'])->controller(PermissionController::class)->group(function () {
    Route::get('/permissoes', 'index')->name('permissions');
    Route::get('/permissao', 'create')->name('permission.create');
    Route::post('/permissao', 'store')->name('permission.store');
    Route::delete('/permissao/{id}', 'destroy')->name('permission.destroy');
});
