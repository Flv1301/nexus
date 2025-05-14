<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Services;

use App\Models\Utils\VerificationCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UserConfirmationCodeService
{

    /**
     * @param string $registration
     * @return VerificationCode
     */
    public function generateCode(string $registration): VerificationCode
    {
        $code = Str::random(6);
        $expiresAt = Carbon::now()->addMinute(5);
        $verificationCode = new VerificationCode();
        $verificationCode->code = $code;
        $verificationCode->registration = $registration;
        $verificationCode->expires_at = $expiresAt;
        $verificationCode->save();

        return $verificationCode;
    }

    /**
     * @param SendCodeVerificationInterface $sendCode
     * @param VerificationCode $verificationCode
     * @param string $destination
     * @return void
     */
    public static function sendCode(SendCodeVerificationInterface $sendCode, VerificationCode $verificationCode, string $destination): void {
        $sendCode->send($verificationCode, $destination);
    }

    /**
     * @param string $code
     * @param string $registration
     * @return VerificationCode|null
     */
    public function verifyCode(string $code, string $registration): ?VerificationCode
    {
        return VerificationCode::where('code', $code)
            ->where('registration', $registration)
            ->where('status', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * @param string $registration
     * @return VerificationCode|null
     */
    public function verifyRegistration(string $registration): ?VerificationCode
    {
        return VerificationCode::where('registration', $registration)
            ->where('status', true)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * @param VerificationCode $verification
     * @return bool
     */
    public function confirmUpdateCode(VerificationCode $verification): bool
    {
        $verificationCode = VerificationCode::where('code', $verification->code)
            ->where('registration', $verification->registration)
            ->where('status', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($verificationCode) {
            $verificationCode->status = true;
            $verificationCode->expires_at = Carbon::now()->addMinute(10);
            $verificationCode->save();

            return true;
        }

        return false;
    }

    /**
     * @param string $registration
     * @param string $email
     * @return bool
     */
    public function reSendCode(string $registration, string $email): bool
    {
        $verification = $this->verifyRegistration($registration);

        if ($verification) {
            $this->sendCode($verification, $email);

            return true;
        }

        return false;
    }
}
