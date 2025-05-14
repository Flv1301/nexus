<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\UserAccessDocumentController;
use App\Http\Controllers\User\UserRegisterController;
use Illuminate\Support\Facades\Route;

Route::get('usuario/cadastro', fn () => view('auth.register.register'))->name('auth.register');
Route::post('usuario/cadastro/verificacao', [RegisterController::class, 'verifyNewRegister'])->name('user.register.verify');
Route::put('usuario/cadastro/novo/{id}', [RegisterController::class, 'update'])->name('user.register.update');
Route::get('usuario/cadastro/novo/{registration}', [RegisterController::class, 'new'])->name('user.register.new');
Route::get('usuario/cadastro/novo/formulario/{registration}', [RegisterController::class, 'viewForm'])->name('user.register.form');
Route::get('usuario/cadastro/novo/codigo', [RegisterController::class, 'new'])->name('user.register.new.code');

Route::middleware(['auth', 'permission:usuario'])->controller(UserRegisterController::class)->group(function () {
    Route::get('/usuarios', 'index')->name('users');
    Route::get('/usuario/ler/{id}', 'show')->name('register.show');
    Route::get('/usuario', 'create')->name('register.create');
    Route::post('/usuario', 'store')->name('register.store');
    Route::get('/usuario/{id}', 'edit')->name('register.edit');
    Route::put('/usuario/{id}', 'update')->name('register.update');
    Route::delete('/usuario/{id}', 'destroy')->name('register.destroy');
});

Route::controller(UserAccessDocumentController::class)->group(function (){
    Route::get('/acesso/termoresponsabilidade', 'show')->name('user.access.contract.show');
    Route::middleware('auth')->get('/acesso/termoresponsabilidade/download', 'download')->name('user.access.document.download');
    Route::get('/acesso/termoresponsabilidade/{id}', 'view')->name('user.access.contract.view');
    Route::post('/acesso/termoresponsabilidade/upload', 'upload')->name('user.access.document.upload');
    Route::put('/acesso/termoresponsabilidade/validacao/{id}', 'documentValidate')->name('user.access.document.validate');
});

