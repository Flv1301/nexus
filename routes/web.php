<?php

use App\Http\Controllers\AquariumController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErbController;
use App\Http\Controllers\LetterControlController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\Perfil\PerfilController;
use App\Http\Controllers\Pix\PixController;
use App\Http\Controllers\Search\ElasticSearchSispController;
use App\Http\Controllers\Service\NotificationController;
use App\Http\Controllers\Tools\ErbMapsController;
use App\Http\Controllers\Tools\IpSearchController;
use App\Http\Controllers\Tools\LocationHistoryController;
use App\Http\Controllers\Tools\ReverseLocationController;
use App\Mail\LetterMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/reset-senha', function () {
    return view('auth.passwords.email');
})
    ->middleware('guest')->name('password.request');

Route::post('/reset-senha', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT ? back()->with(['status' => __($status)]) : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.reset');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password)])
                ->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::middleware(['auth'])->controller(DashboardController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/dashboard', 'index')->name('dashboard');
//    Route::get('/chat-gpt/{m?}', fn($message) => ChatGPTService::sendMessage($message))->name('chat.send');
});
/** PERFIL */
Route::middleware('auth')->controller(PerfilController::class)->group(function () {
    Route::get('/perfil', 'show')->name('perfil');
    Route::get('/perfil/password', 'password')->name('perfil.password');
    Route::post('/perfil/reset', 'reset')->name('perfil.reset');
    Route::put('/perfil/assinatura/{id}', 'signature')->name('perfil.ass');
});

/** DOWNLOADS */
Route::middleware('auth')->get('/download/{path}', fn($path) => response()->download(Storage::path(decrypt($path)))
)->name('download');

/** USUARIO */
require_once 'routes/user.php';
/** PESSOAS */
require_once 'routes/person.php';
/** CASO */
require_once 'routes/case.php';
/** SISTEMA */
require_once 'routes/system.php';
/** SUPPORTS */
require_once 'routes/support.php';
/** HTTP API */
require_once 'routes/apis.php';
/** FUNÇÕES */
require_once 'routes/role.php';
/** PERMISSÕES */
require_once 'routes/permission.php';
/** UNIDADE */
require_once 'routes/unity.php';
/** SETOR */
require_once 'routes/sector.php';
/** GI2 */
require_once 'routes/gi2.php';
/** IMEI */
require_once 'routes/imei.php';
/** TELEPHONE */
require_once 'routes/telephone.php';
/** PESQUISAS */
require_once 'routes/search.php';
/** NOTIFICACAO */
require_once 'routes/notification.php';


Route::middleware(['auth'])->get('/aquarium', [AquariumController::class, 'index'])->name('aquarium.index');
Route::middleware(['auth'])->get('/estacaoerb', [ErbMapsController::class, 'index'])->name('erb.maps');
Route::middleware(['auth'])->get('/ferramentas/pesquisa/ip', [IpSearchController::class, 'index'])->name('tools.search.ip');

Route::middleware(['auth'])->get('/erb/', [ErbController::class, 'index'])->name('erb.index');
Route::middleware(['auth'])->get('/erb/show/honeycomb', [ErbController::class, 'honeycomb'])->name('erb.show.honeycomb');
Route::middleware(['auth'])->get('/erb/dualmap', [ErbController::class, 'dualmap'])->name('erb.dualmap');
Route::middleware(['auth'])->post('/erb/show/honeycombgoogle', [ErbController::class, 'honeycombGoogle'])->name('erb.show.honeycombgoogle');

Route::middleware(['auth', 'permission:gi2'])->controller(LetterController::class)->group(function () {
    Route::get('/oficios', 'index')->name('letters');
    Route::post('/oficio', 'generateLetter')->name('letter.create');
    Route::post('/oficio/gi2', 'createReportOperator')->name('gi2.oficio');
});
Route::middleware(['auth', 'permission:oficio'])->controller(LetterControlController::class)->group(function () {
    Route::get('/controle/oficios', 'index')->name('letter.control.index');
    Route::post('/controle/oficio', 'store')->name('letter.control.store');
    Route::get('/controle/oficio/{id}', 'edit')->name('letter.control.edit');
    Route::put('/controle/oficio/{id}', 'update')->name('letter.control.update');
});

Route::middleware(['auth'])->get('/mail', fn() => new LetterMail())->name('seap.mail');

Route::middleware(['auth'])->get('/notificacao', [NotificationController::class, 'index'])->name('notifications');
Route::middleware(['auth'])->put('/notificacao/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
//Route::get('sisfac/migrate', [SisfacMigrateDbController::class, 'migrate']);

Route::get('/ferramentas/reverse-location', [ReverseLocationController::class, 'index'])->name('reverse-location');
Route::post('/ferramentas/reverse-location', [ReverseLocationController::class, 'plotMap'])->name('reverse-location-map');
Route::get('/ferramentas/location-history', [LocationHistoryController::class, 'index'])->name('location-history');
Route::post('/ferramentas/location-history', [LocationHistoryController::class, 'plotMap'])->name('location-history-map');

Route::get('/pesquisa/pix', [PixController::class, 'index'])->name('pesquisa.pix.index');
Route::get('/pesquisa/pix/search', [PixController::class, 'search'])->name('pesquisa.pix.search');
//Route::post('/pesquisa/relato', [ElasticSearchSispController::class, 'elastic'])->name('pesquisa.relato.elastic');
//Route::get('/pesquisa/relato/show/', [ElasticSearchSispController::class, 'show'])->name('pesquisa.relato.show');
