<?php

namespace App\Notifications\Subscription;

use App\Providers\RouteServiceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    private string $subject;
    private string $message;

    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->from('noreply@envyclient.com')
            ->greeting("Hello $notifiable->name,")
            ->line($this->message)
            ->action('Manage Subscription', url(RouteServiceProvider::SUBSCRIPTIONS));
    }

}
