<?php

use App\Http\Controllers\User\UserRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:usuario'])->controller(UserRegisterController::class)->group(function () {
    Route::get('/usuarios', 'index')->name('users');
    Route::get('/usuario/ler/{id}', 'show')->name('register.show');
    Route::get('/usuario', 'create')->name('register.create');
    Route::post('/usuario', 'store')->name('register.store');
    Route::get('/usuario/{id}', 'edit')->name('register.edit');
    Route::put('/usuario/{id}', 'update')->name('register.update');
    Route::delete('/usuario/{id}', 'destroy')->name('register.destroy');
});
