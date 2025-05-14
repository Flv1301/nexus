<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 01/05/2023
 * @copyright NIP CIBER-LAB @2023
 */

use App\APIs\CacadorRoraimaApi;
use App\APIs\CortexApi;
use App\APIs\EscavadorApi;
use App\APIs\ProdepaApi;
use App\APIs\SeducAPI;
use App\APIs\SynergyeApi;
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
/** ################### */

/** PRODEPA */
Route::middleware('auth')->get('/prodepa/registrocivil/{name}/{lastname}/{birthdate}/{father?}/{mother?}/{rg?}',
    fn($name, $lastname, $birthdate, $father = '', $mother = '', $rg = '') => (new ProdepaApi())->civilIdentification(
        $name,
        $lastname,
        $birthdate,
        $father,
        $mother,
        $rg
    )
);
Route::middleware('auth')->get('/prodepa/busca/documento/{rg?}/{cpf?}',
    fn($rg = '', $cpf = '') => (new ProdepaApi())->documentSearch($rg, $cpf)
);
Route::middleware('auth')->get('/prodepa/busca/nome/{name}/{lastname}/{mother?}/{father?}',
    fn($name, $lastname, $mother = '', $father = '') => (new ProdepaApi())->nameSearch(
        $name,
        $lastname,
        $mother,
        $father
    )
);

/** ##################### */

Route::middleware('auth')->get('/escavador/busca/{terms}/{options?}/{limit?}/{page?}',
    fn(string $terms, string $options = 't', string $limit = '20', int $page = 1) => (new EscavadorApi())->termsSearch(
        $terms,
        $options,
        $limit,
        $page
    )
);
/** ##################### */

/** SEDUC API */
Route::middleware('auth')->get('/seduc/buscar/mae/{mother}', fn($mother) => (new SeducAPI())
    ->searchStudentsForMother($mother));
Route::middleware('auth')->get('/seduc/buscar/pai/{father}', fn($father) => (new SeducAPI())
    ->searchStudentsForFather($father));
Route::middleware('auth')->get('/seduc/buscar/aluno/{name}/pai/{father}',
    fn($name, $father) => (new SeducAPI())->searchStudentsWithFather($name, $father));
Route::middleware('auth')->get('/seduc/buscar/aluno/{name}/mae/{mother}',
    fn($name, $mother) => (new SeducAPI())->searchStudentsWithMother($name, $mother));
Route::middleware('auth')->get('/seduc/{name}/{mother?}/{father?}',
    fn($name, $mother = '', $father = '') => (new SeducAPI())->searchStudents($name, $mother, $father));
/** ##################### */

/** MONITORADOS SEAP */
Route::middleware(['auth'])->get('/monitoramento/preso/{id}', function (string $id) {
    return SynergyeApi::searchLocationById($id);
})->name('seap.api.monitor.stuck');
/** ##################### */

/** IP INFO */
Route::middleware(['auth'])->get('pesquisa/ip', [IpSearchController::class, 'search'])->name('search.ip');

/** CASSADOR RORAIMA */
Route::middleware(['auth', 'permission:cacador'])->get('/cassador-rr', [CacadorRoraimaApi::class, 'personSearch'])->name('cassador.api.search.persons');
Route::middleware(['auth', 'permission:cacador'])->get('/cassador-rr/{id}', [CacadorRoraimaApi::class, 'personShow'])->name('cassador.api.show.persons');
Route::middleware(['auth', 'permission:cacador'])->get('/cassador-rr/image/{id}', [CacadorRoraimaApi::class, 'personImage'])->name('cassador.api.person.image');
/** ##################### */
