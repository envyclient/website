<?php

namespace App\Notifications\LicenseRequest;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseRequestDeniedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $message,
    )
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Media License - Denied')
            ->markdown('emails.license-request-denied', ['user' => $notifiable, 'message' => $this->message]);
    }

}
