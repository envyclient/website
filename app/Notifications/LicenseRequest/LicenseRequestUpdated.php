<?php

namespace App\Notifications\LicenseRequest;

use App\Providers\RouteServiceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseRequestUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $greeting,
        public string $subject,
        public string $message,
        public string $message2 = '',
    ){}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('noreply@envyclient.com')
            ->subject($this->subject)
            ->greeting($this->greeting)
            ->line($this->message)
            ->line($this->message2)
            ->action('Dashboard', route('dashboard'));
    }

}
