<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterControlController;
use App\Http\Controllers\Perfil\PerfilController;
use App\Http\Controllers\Pix\PixController;
use App\Http\Controllers\Service\NotificationController;
use App\Http\Controllers\Tools\IpSearchController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\ProfileController;

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/reset-senha', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

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
            $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
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
});

/** PERFIL */
Route::middleware('auth')->controller(PerfilController::class)->group(function () {
    Route::get('/perfil', 'show')->name('perfil');
    Route::get('/perfil/password', 'password')->name('perfil.password');
    Route::post('/perfil/reset', 'reset')->name('perfil.reset');
});

/** DOWNLOADS */
Route::middleware('auth')->get('/download/{path}', fn($path) => response()->download(Storage::path(decrypt($path)))
)->name('download');

/** USUARIO */
require_once 'routes/user.php';
/** PESSOAS */
require_once 'routes/person.php';
/** ENDEREÇOS */
require_once 'routes/address.php';
/** CASO */
require_once 'routes/case.php';
/** SISTEMA */
require_once 'routes/system.php';
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
/** TELEPHONE */
require_once 'routes/telephone.php';
/** PESQUISAS */
require_once 'routes/search.php';
/** NOTIFICACAO */
require_once 'routes/notification.php';

Route::middleware(['auth'])->get('/ferramentas/pesquisa/ip', [IpSearchController::class, 'index'])->name('tools.search.ip');

Route::middleware(['auth', 'permission:oficio'])->controller(LetterControlController::class)->group(function () {
    Route::get('/controle/oficios', 'index')->name('letter.control.index');
    Route::post('/controle/oficio', 'store')->name('letter.control.store');
    Route::get('/controle/oficio/{id}', 'edit')->name('letter.control.edit');
    Route::put('/controle/oficio/{id}', 'update')->name('letter.control.update');
});

Route::middleware(['auth'])->get('/notificacao', [NotificationController::class, 'index'])->name('notifications');
Route::middleware(['auth'])->put('/notificacao/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');

Route::get('/pesquisa/pix', [PixController::class, 'index'])->name('pesquisa.pix.index');
Route::get('/pesquisa/pix/search', [PixController::class, 'search'])->name('pesquisa.pix.search');

/** LOCALIZAÇÃO - APIs para busca de cidades */
Route::middleware(['auth'])->group(function () {
    Route::get('/api/cities-by-uf', [App\Http\Controllers\LocationController::class, 'getCitiesByUF'])->name('api.cities.by.uf');
    Route::get('/api/search-cities', [App\Http\Controllers\LocationController::class, 'searchCities'])->name('api.search.cities');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
