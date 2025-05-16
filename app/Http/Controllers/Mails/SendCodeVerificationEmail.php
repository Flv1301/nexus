<?php

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
