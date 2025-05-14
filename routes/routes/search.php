<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\APIs\SynergyeApi;
use App\Http\Controllers\Search\PersonSearchController;
use App\Http\Controllers\Search\SearchDatabaseAdvancedController;
use App\Http\Controllers\Search\VehicleSearchController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\VCard\VCardController;
use App\Models\Utils\Databases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:veiculo'])->get('/veiculo', fn() => view('search.vehicle.index'))->name('vehicle.index');
Route::middleware(['auth', 'permission:vcard'])->get('/vcard', [VCardController::class, 'index'])->name('vcard.index');
Route::middleware(['auth', 'permission:vcard'])->post('/vcard', [VCardController::class, 'search'])->name('vcard.search.phone');
Route::middleware(['auth', 'permission:bilhetagem'])->get('/bilhetagem', [TicketController::class, 'index'])->name('ticket.search.index');
Route::middleware(['auth', 'permission:bilhetagem'])->post('/bilhetagem/', [TicketController::class, 'search'])->name('ticket.search.ticket');

/** BUSCA BASE AVANÇADA */
Route::middleware(['auth', 'permission:pesquisa_avancada'])->controller(SearchDatabaseAdvancedController::class)->group(
    function () {
        Route::get('/pesquisa/avancada/', fn () => view('search.advanced.index', ['database' => '']))->name('search.advanced.index');
        Route::get('/modal/pesquisa/avancada/bases/', 'show')->name('search.advanced.show');
        Route::get('/pesquisa/avancada/bases/', 'search')->name('search.advanced.search');
    }
);

Route::middleware(['auth', 'permission:pesquisa_avancada'])->get('/base/pesquisa', fn(Request $request) => Databases::getTableColumnsName($request->b))->name('search.bases.columns');

/** BUSCA PESSOA COMPLETA */
Route::middleware(['auth', 'permission:pesquisa_pessoa_completa'])->controller(PersonSearchController::class)->group(function () {
    Route::middleware(['auth'])->get('/pesquisa/pessoa', 'index')->name('person.search.index');
    Route::middleware(['auth'])->get('/pesquisa/pessoa/{base}/{id}', 'show')->name('person.search.show');
    Route::middleware(['auth'])->post('/pesquisa/pessoa', 'search')->name('person.search');
});

/** SEAP MAPS */
Route::middleware(['auth'])->get('/mapa/monitoracao/preso/{id}', function($id) {
    $response = SynergyeApi::searchLocationById($id);
    return view('seap.maps_monitor_stuck', ['response' => $response]);
})->name('seap.maps.monitor.stuck');

/** VEICULOS */
Route::middleware(['auth'])->controller(VehicleSearchController::class)->group( function (){
   Route::get('/pesquisa/veiculo/{plate?}', 'search')->name('search.vehicle.plate');
});
