<?php

namespace App\Services;

use App\Models\Utils\VerificationCode;

interface SendCodeVerificationInterface
{
    /**
     * @param VerificationCode $verificationCode
     * @param string $destination
     * @return void
     */
    public function send(VerificationCode $verificationCode, string $destination): void;
}
