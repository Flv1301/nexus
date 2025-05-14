<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Auth;

use App\Enums\CodeControllerUserEnum;
use App\Enums\TypeEmailNotificationsEnum;
use App\Http\Controllers\Controller;
use App\Models\Data\Address;
use App\Models\Departament\Sector;
use App\Models\Departament\Unity;
use App\Models\Srh\Srh;
use App\Models\User;
use App\Notifications\UserNewRegistertNotification;
use App\Services\LogUserAccessService;
use App\Services\NotificationService;
use App\Services\UserConfirmationCodeService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * @param Request $request
     * @param UserConfirmationCodeService $verificationService
     * @return RedirectResponse
     */
    public function verifyNewRegister(Request $request, UserConfirmationCodeService $verificationService): RedirectResponse
    {
//        $request->validate(['registration' => 'required|string|max:10', 'g-recaptcha-response' => 'required|captcha',]);
        $request->validate(['registration' => 'required|string|max:10']);

        try {
            $registration = $request->registration;
            $ip = (new LogUserAccessService())->getPublicIp();
            activity()->useLog('Create User')->log(
                "Validação de Matricula {$registration} em cadastro de usuário. Dados de acesso {$ip}"
            );

            $user = $this->isRegistered($registration);

            if ($user) {
                return $this->userAlreadyRegistered();
            }

            $srh = $this->getSrh($registration);

            if (!$srh) {
                return $this->invalidRegistration();
            }

            return $this->redirectNewRegistration($registration);

            if ($request->filled('code') || true) {
                $verificationCode = $verificationService->verifyCode($request->code, $registration);

                if (!$verificationCode) {
                    return $this->notificationCodeEmail();
                }

                $verificationService->confirmUpdateCode($verificationCode);
                return $this->redirectNewRegistration($registration);
            }


//            if ($verificationCode = $verificationService->generateCode($registration)) {
//                $verificationService->sendCode(new SendCodeVerificationEmail(), $verificationCode, $srh->email);
//                return $this->notificationCodeEmail();
//            }

            return $this->verificationCodeGenerationFailed();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->verificationCodeGenerationFailed();
        }
    }

    /**
     * @return RedirectResponse
     */
    private function userAlreadyRegistered(): RedirectResponse
    {
        toast('Usuário já cadastrado!', 'info');
        return back();
    }

    /**
     * @param $registration
     * @return RedirectResponse
     */
    private function redirectNewRegistration($registration): RedirectResponse
    {
        return redirect()->route('user.register.new', ['registration' => $registration]);
    }

    /**
     * @return RedirectResponse
     */
    private function notificationCodeEmail(): RedirectResponse
    {
        toast('Verifique o código enviado para seu e-mail!', 'info');
        return redirect()->back()->with('code', '')->withInput();
    }

    /**
     * @return RedirectResponse
     */
    private function verificationCodeGenerationFailed(): RedirectResponse
    {
        toast('Não foi possível gerar o código de verificação!', 'info');
        return back()->withInput();
    }

    /**
     * @return RedirectResponse
     */
    private function invalidRegistration(): RedirectResponse
    {
        toast('A Matrícula informada não consta na base de dados ou está fora de atividade.', 'info');
        return back()->withInput();
    }

    /**
     * @return RedirectResponse
     */
    private function invalidVerificationCode(): RedirectResponse
    {
        toast('Código de verificação expirado!.', 'info');
        return back()->withInput();
    }

    /**
     * @param string $registration
     * @return mixed
     */
    private function getSrh(string $registration)
    {
        return Srh::where('matricula', $registration)->whereIn('situacao', ['EM ATIVIDADE', 'FERIAS'])->first();
    }

    /**
     * @param $registration
     * @param UserConfirmationCodeService $verificationService
     * @return RedirectResponse|View
     */
    public function new($registration, UserConfirmationCodeService $verificationService): RedirectResponse|View
    {

//        if (!$verificationService->verifyRegistration($registration)) {
//            return $this->invalidVerificationCode();
//        }

        $user = $this->isRegistered($registration);

        if ($user) {
            return $this->userAlreadyRegistered();
        }

        $user = User::where('registration', $registration)->where('status', false)
            ->where('code_controller', CodeControllerUserEnum::CADASTRADO->name)
            ->first();

        if ($user) {
            return redirect()->route('user.register.form', $registration);
        }

        $srh = $this->getSrh($registration);
        $user = new User();
        $user->name = $srh->nome_servidor;
        $user->nickname = Str::of($srh->nome_servidor)->before(' ');
        $user->registration = $srh->matricula;
        $user->birth_date = Carbon::parse($srh->nascimento)->format('d/m/Y');
        $user->cpf = $srh->cpf;
        $user->email = $srh->email;
        $user->password = bcrypt(Str::random(8));
        $user->office = "Administrativo";
        $user->role = "Usuario";
        $user->unity_id = 1;
        $user->sector_id = 1;
        $user->code_controller = CodeControllerUserEnum::CADASTRADO->name;
        $user->user_creator = $srh->nome_servidor;
        $user->save();
        $user->assignRole('Usuario');

        return redirect()->route('user.register.form', $registration);
    }

    public function viewForm($registration)
    {
        $user = User::where('registration', $registration)->where('status', false)
            ->where('code_controller', CodeControllerUserEnum::CADASTRADO->name)
            ->first();

        if (!$user) {
            return back()->withInput();
        }

        if (!Auth::check()) {
            Auth::loginUsingId($user->id);
        }

        $unitys = Unity::all();
        $sectors = Sector::where('unity_id', $user->unity_id)->get();
        $address = new Address();

        return view('auth.register.form', compact('user', 'unitys', 'sectors', 'address',));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'required|string|min:6',
            'office' => 'required|string|max:255',
            'birth_date' => 'required',
            'cpf' => 'required|string',
            'registration' => 'required|string',
            'unity_id' => 'required|integer',
            'sector_id' => 'required|integer',
            'ddd' => 'required',
            'telephone' => 'required',
        ]);
        try {
            $user = User::findOrFail($id);
            $user->fill($request->except('password'));
            $user->password = bcrypt($request->password);
            $user->update();
            $this->notification();

            toast('Cadastro realizado!. O setor responsável irá fazer a validação e liberação em até 3 dias úteis.', 'info');
            return redirect()->route('auth.register');
        } catch (ModelNotFoundException $exception) {
            Log::error($exception->getMessage());

            toast('Não fo possível realizar o cadastro. Tente novamente mais tarde!', 'error');
            return back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @param UserConfirmationCodeService $verificationService
     * @return void
     */
    public function reSendCode(Request $request, UserConfirmationCodeService $verificationService): void
    {
        $srh = $this->getSrh($request->registration);
        if ($srh) {
            $verificationService->reSendCode($request->registration, $srh->email);
        }
    }

    /**
     * @return void
     */
    private function notification(): void
    {
        NotificationService::send(TypeEmailNotificationsEnum::REGISTRATION_RELEASE->value, new UserNewRegistertNotification());
    }

    /**
     * @param mixed $registration
     * @return int
     */
    public function isRegistered(mixed $registration): int
    {
        return User::where('registration', $registration)
            ->whereIn('code_controller', [
                CodeControllerUserEnum::CONCLUIDO->name,
                CodeControllerUserEnum::PENDENTE->name,
                CodeControllerUserEnum::CANCELADO->name,
                CodeControllerUserEnum::CADASTRADO->name,
            ])->count();
    }
}
