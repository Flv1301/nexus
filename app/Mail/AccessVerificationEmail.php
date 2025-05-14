<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

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
        return $this->view('mail.access_mail_verification')->subject('Código de verificação do sistema Hydra.');
    }
}
