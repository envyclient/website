<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ClientNotification extends Notification
{
    public function __construct(
        public string $type,
        public string $message,
    ){}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
        ];
    }
}
