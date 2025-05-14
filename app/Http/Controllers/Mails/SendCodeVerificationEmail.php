<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Http\Controllers\Mails;

use App\Mail\AccessVerificationEmail;
use App\Models\Utils\VerificationCode;
use App\Services\SendCodeVerificationInterface;
use Illuminate\Support\Facades\Mail;

class SendCodeVerificationEmail implements SendCodeVerificationInterface
{
    /**
     * @param VerificationCode $verificationCode
     * @param string $destination
     * @return void
     */
    public function send(VerificationCode $verificationCode, string $destination): void
    {
        Mail::to($destination)->send(new AccessVerificationEmail($verificationCode));
    }
}
