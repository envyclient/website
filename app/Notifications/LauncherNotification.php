<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LauncherNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $name;
    private bool $beta;
    private string $changelog;

    public function __construct(string $name, bool $beta, string $changelog)
    {
        $this->name = $name;
        $this->beta = $beta;
        $this->changelog = $changelog;
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
            'name' => $this->name,
            'beta' => $this->beta,
            'changelog' => $this->changelog,
        ];
    }
}
