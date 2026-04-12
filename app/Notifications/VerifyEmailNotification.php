<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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

    protected function verificationUrl($notifiable): string
    {
        $apiSignedUrl = URL::temporarySignedRoute('verification.verify', Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)), [
            'id' => $notifiable->getKey(),
            'hash' => sha1($notifiable->getEmailForVerification()),
        ]);

        $parsed = parse_url($apiSignedUrl);
        parse_str($parsed['query'] ?? '', $queryParams);

        $id = $notifiable->getKey();
        $hash = sha1($notifiable->getEmailForVerification());

        $frontendUrl = rtrim(config('app.frontend_url', env('FRONTEND_URL', 'http://localhost:3000')), '/');

        return $frontendUrl.'/auth/verify-email?'.
            http_build_query([
                'id' => $id,
                'hash' => $hash,
                'expires' => $queryParams['expires'] ?? '',
                'signature' => $queryParams['signature'] ?? '',
            ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifique seu endereço de e-mail')
            ->greeting('Olá, '.$notifiable->name.'!')
            ->line('Clique no botão abaixo para verificar seu e-mail e ativar sua conta.')
            ->action('Verificar E-mail', $url)
            ->line('Este link expirará em 60 minutos.')
            ->line('Se você não criou esta conta, ignore este e-mail.')
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
