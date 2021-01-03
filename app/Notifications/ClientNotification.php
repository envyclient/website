<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ClientNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private float $version;
    private string $message;

    public function __construct(float $version, string $message)
    {
        $this->version = $version;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'version' => $this->version,
            'message' => $this->message,
        ];
    }
}
