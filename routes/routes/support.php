<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\Http\Controllers\SupportController;
use App\Http\Controllers\VideoTrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->controller(SupportController::class)->group(function () {
    Route::get('/suportes', 'index')->name('supports');
    Route::get('/suporte/ler/{id}', 'show')->name('support.show');
    Route::get('/suporte/responder/{id}', 'torespond')->name('support.torespond');
    Route::post('/suporte/resposta/{id}', 'response')->name('support.response');
    Route::get('/suporte/resposta/{id}', 'responseShow')->name('support.response.show');
    Route::get('/suporte/historico/{id}', 'history')->name('support.history');
    Route::get('/suporte', 'create')->name('support.create');
    Route::post('/suporte', 'store')->name('support.store');
    Route::get('/suporte/{id}', 'edit')->name('support.edit');
    Route::put('/suporte/{id}', 'update')->name('support.update');
    Route::delete('/suporte/{id}', 'destroy')->name('support.destroy');
});

Route::middleware(['auth'])->controller(VideoTrainingController::class)->group(function () {
    Route::get('/suporte/categorias/videos', 'index')->name('support.videos');
    Route::get('/suporte/categorias/videos/player/{filename}', 'player')->name('support.videos.player');
    Route::get('/suporte/categorias/videos/{filename}', 'stream')->name('support.videos.stream');
});
