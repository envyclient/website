<?php

namespace App\Http\Livewire\Notification;

use Illuminate\Support\Facades\DB;
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
            'notifications' => $notifications,
        ]);
    }

    public function delete(string $id)
    {
        $version = DB::table('notifications')
            ->where('id', $id)
            ->first();

        DB::table('notifications')
            ->where('data', $version->data)
            ->delete();
    }
}
