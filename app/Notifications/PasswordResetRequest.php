<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    use Queueable;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $data = [];
        $data['url']      = env('FRONTEND_URL') . "/reset-password/" . $this->token;
        $data['path']     = $data['path'] ?? env('BASE_URL');
        $data['founder']  = $data['founder'] ?? env('FOUNDER');
        $data['first_name']  = $data['first_name'] ?? $notifiable['first_name'];
        return (new MailMessage())
            ->view('vendor.notifications.email', ['data' => $data])
            ->line('You are receiving this email because we  received a password reset request for your account.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
