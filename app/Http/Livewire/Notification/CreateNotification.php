<?php

namespace App\Http\Livewire\Notification;

use App\Models\User;
use App\Notifications\ClientNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CreateNotification extends Component
{
    public $type = 'info';
    public $notification;

    protected $rules = [
        'type' => 'required|string',
        'notification' => 'required|string',
    ];

    public function render()
    {
        return view('livewire.notification.create-notification');
    }

    public function submit()
    {
        $this->validate();

        // send the notification to all users
        Notification::send(
            User::all(),
            new ClientNotification($this->type, $this->notification),
        );

        $this->emit('NOTIFICATION_CREATED');

        $this->reset();
    }
}
