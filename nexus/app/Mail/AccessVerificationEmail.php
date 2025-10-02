<?php

namespace App\Mail;

use App\Models\Utils\VerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessVerificationEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var VerificationCode
     */
    public VerificationCode $verificationCode;

    /**
     * @param VerificationCode $verificationCode
     */
    public function __construct(VerificationCode $verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return AccessVerificationEmail
     */
    public function build(): AccessVerificationEmail
    {
        return $this->view('mail.access_mail_verification')->subject('Código de verificação do sistema Nexus.');
    }
}
