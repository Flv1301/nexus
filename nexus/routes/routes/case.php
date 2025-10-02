<?php

use App\Http\Controllers\Case\CaseAnalysisController;
use App\Http\Controllers\Case\CaseController;
use App\Http\Controllers\Case\CaseProcedureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:caso'])->controller(CaseController::class)->group(function () {
    Route::get('/casos', 'index')->name('cases');
    Route::get('/caso/cadastro', 'create')->name('case.create');
    Route::post('/caso/cadastro', 'store')->name('case.store');
    Route::get('/caso/{id}', 'edit')->name('case.edit');
    Route::put('/caso/{id}', 'update')->name('case.update');
    Route::delete('/caso/{id}', 'destroy')->name('case.destroy');
});

/** ANALISE DE CASO */
Route::middleware(['auth', 'permission:caso.ler'])->controller(CaseAnalysisController::class)->group(function () {
    Route::get('/caso/analise/{id}', 'show')->name('case.analysis');
    Route::get('/caso/analise/arquivo/{id}', 'file')->name('case.file');
    Route::delete('/caso/analise/arquivo/{id}', 'destroy')->name('case.file.destroy');
    Route::post('/caso/analise/arquivo/{id}', 'attachment')->name('case.file.upload');
    Route::get('/caso/analise/arquivo/csv/{id}', 'fileCsv')->name('case.file.csv');
});

/** TRAMITAÇÃO DE CASO */

Route::middleware(['auth', 'permission:tramitacao'])->controller(CaseProcedureController::class)->group(
    function () {
        Route::get('/tramitacoes', 'index')->name('procedures');
        Route::get('/caso/tramitacao/{id}', 'show')->name('procedure.show');
        Route::get('/tramitacao', 'find')->name('procedure.find');
        Route::post('/caso/tramitacao/{id}', 'create')->name('procedure.create');
        Route::post('/tramitacao', 'response')->name('procedure.response.create');
        Route::get('/tramitacao/respostas/{id}', 'responses')->name('procedure.responses');
        Route::get('/tramitacao/resposta/{id}', 'responseView')->name('procedure.response.view');
        Route::delete('/tramitacao/{id}', 'destroy')->name('procedure.destroy');
    }
);
