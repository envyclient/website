<?php

namespace App\Notifications\LicenseRequest;

use App\Models\LicenseRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseRequestApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Media License Approved')
            ->greeting("Congrats $notifiable->name,")
            ->line('Your media license request has been approved and you have ' . LicenseRequest::DAYS_TO_ADD . ' days to publish a video of the client.')
            ->action('Download Launcher', route('home'))
            ->line('Please visit the website to download the launcher.');
    }
}
