<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

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
