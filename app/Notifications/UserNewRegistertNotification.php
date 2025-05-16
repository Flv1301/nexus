<?php

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
            ->line('Verificar no sistema Nexus as informações para liberação do acesso.');
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
