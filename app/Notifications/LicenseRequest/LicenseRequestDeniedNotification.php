<?php

namespace App\Notifications\LicenseRequest;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseRequestDeniedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $message,
    ){}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('noreply@envyclient.com')
            ->subject('Media License Denied')
            ->markdown('emails.license-request-denied', ['user' => $notifiable, 'message' => $this->message]);
    }

}
