<?php

use App\APIs\CortexApi;
use App\APIs\EscavadorApi;
use App\APIs\ZipCodeBr;
use App\Http\Controllers\Tools\IpSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/zipcode/{code}', fn($code) => ZipCodeBr::zipCode($code));

/** CORTEX */
Route::middleware('auth')->get('/cortex/cpf/{cpf}', fn($cpf) => (new CortexApi())->personSearchCPF($cpf));
Route::middleware('auth')->get('/cortex/{name}/{birthDate}',
    fn($name, $birthDate) => (new CortexApi())->personSearchNameAndBirthDate($name, $birthDate)
);
Route::middleware('auth')->get('/cortex/{name}/mae/{mother}',
    fn($name, $mother) => (new CortexApi())->personSearchNameAndMother($name, $mother)
);
Route::middleware('auth')->get('/cortex/bnmp/codigo/{id}', fn($id) => (new CortexApi())
    ->personSearchBnmpId($id));
Route::middleware('auth')->get('/cortex/bnmp/nome/{name}', fn($name) => (new CortexApi())
    ->personSearchBnmpName($name));
Route::middleware('auth')->get('/cortex/bnmp/cpf/{cpf}', fn($cpf) => (new CortexApi())
    ->personSearchBnmpCpf($cpf));
Route::middleware('auth')->get('/cortex/bnmp/{name}/mae/{mother}',
    fn($name, $mother) => (new CortexApi())->personSearchBnmpNameAndMother($name, $mother)
);
Route::middleware('auth')->get('/cortex/bnmp/{name}/{birthDate}',
    fn($name, $birthDate) => (new CortexApi())->personSearchBnmpNameAndBirthDate($name, $birthDate)
);
Route::middleware(['auth', 'permission:veiculo'])->get('/cortex/renach/{cpf}', fn($cpf) => (new CortexApi())
    ->personSearchRenach($cpf));

Route::middleware(['auth', 'permission:veiculo'])->get('/cortex/veiculo/placa/{plate?}', fn($plate) => (new CortexApi())
    ->vehiclePlateAndMoviment($plate))->name('cortex.vehicle.plate');

Route::middleware('auth')->get('/escavador/busca/{terms}/{options?}/{limit?}/{page?}',
    fn(string $terms, string $options = 't', string $limit = '20', int $page = 1) => (new EscavadorApi())->termsSearch(
        $terms,
        $options,
        $limit,
        $page
    )
);

/** IP INFO */
Route::middleware(['auth'])->get('pesquisa/ip', [IpSearchController::class, 'search'])->name('search.ip');
