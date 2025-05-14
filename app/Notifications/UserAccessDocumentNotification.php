<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 10/08/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccessDocumentNotification extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    public User $user;

    /**
     * @param User|Authenticatable $user
     */
    public function __construct(User|Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())->view('mail.user_document_upload', ['user' => $this->user]);
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Liberação de Termo de Responsabilidade: {$this->user->name} MAT. {$this->user->registration}"
        ];
    }
}
