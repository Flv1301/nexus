<?php

use App\Http\Controllers\Data\AddressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/endereco/{id}/editar', [AddressController::class, 'edit'])->name('address.edit');
    Route::put('/endereco/{id}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/endereco/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
}); 