<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly string $token)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function frontendUrl($notifiable): string
    {
        $frontend = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');

        return $frontend.'/reset-password?'.http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->frontendUrl($notifiable);

        return (new MailMessage)
            ->subject('Redefinição de senha')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('Recebemos uma solicitação para redefinir a senha da sua conta.')
            ->action('Redefinir Senha', $url)
            ->line('Este link expirará em '.config('auth.passwords.users.expire', 60).' minutos.')
            ->line('Se você não solicitou a redefinição, ignore este e-mail — sua senha permanece inalterada.')
            ->salutation('Atenciosamente, '.config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
