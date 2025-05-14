<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNewRegistertNotification extends Notification
{
    use Queueable;

    public function __construct()
    {

    }

    /**
     * @param $notifiable
     * @return string[]
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->line("Cadastro de novo usuário realizado: {$notifiable->name}")
            ->line('Verificar no sistema Hydra as informações para liberação do acesso.');
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            'message' => 'Cadastro de Novo Usuário Mat. ' . $notifiable->registration
        ];
    }
}
