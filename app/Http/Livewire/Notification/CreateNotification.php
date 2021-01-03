<?php

namespace App\Http\Livewire\Notification;

use App\Models\User;
use App\Notifications\ClientNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class CreateNotification extends Component
{
    public $version;
    public $notification;

    protected $rules = [
        'version' => 'required|numeric|min:5.0',
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
            User::has('subscription')->get(),
            new ClientNotification($this->version, $this->notification),
        );

        $this->version = null;
        $this->notification = null;

        $this->emit('NOTIFICATION_CREATED');
    }
}
