<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Genera la URL con la ruta nombrada para resetear contraseña
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject(Lang::get('Restablecimiento de contraseña'))
            ->view('emails.password_reset', [
                'actionUrl' => $resetUrl,
                'displayableActionUrl' => str_replace(['http://', 'https://'], '', $resetUrl),
                'user' => $notifiable,
                'url' => url('/'),
                'token' => $this->token,
                'count' => config('auth.passwords.users.expire', 60),
                'appName' => config('app.name'),
                'currentYear' => date('Y')
            ]);
    }
}
