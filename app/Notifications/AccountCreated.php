<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $password,
    ){}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('noreply@envyclient.com')
            ->subject('Account Created')
            ->markdown('emails.account-created', ['name' => $notifiable->name, 'password' => $this->password]);
    }
}
