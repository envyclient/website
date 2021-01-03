<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;
use Livewire\WithPagination;

class ListNotifications extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['NOTIFICATION_CREATED' => '$refresh'];

    public function render()
    {
        $notifications = auth()
            ->user()
            ->notifications()
            ->paginate(10);


        return view('livewire.notification.list-notifications', [
            'notifications' => $notifications
        ]);
    }
}
