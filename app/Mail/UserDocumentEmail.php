<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 02/10/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserDocumentEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserDocumentEmail
     */
    public function build(): UserDocumentEmail
    {
        return $this->view('mail.user_document_upload')->subject('Verificação de Documentação de Usuário.');
    }
}
