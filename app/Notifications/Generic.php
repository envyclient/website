<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Generic extends Notification implements ShouldQueue
{
    use Queueable;

    private $user, $message, $subject;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $message
     * @param string $subject
     */
    public function __construct(User $user, string $message, string $subject)
    {
        $this->user = $user;
        $this->message = $message;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        // TODO: change email title
        return (new MailMessage)
            ->subject($this->subject)
            ->from('noreply@envyclient.com')
            ->greeting('Hello ' . $this->user->name)
            ->line($this->message)
            ->action('Visit Website', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
