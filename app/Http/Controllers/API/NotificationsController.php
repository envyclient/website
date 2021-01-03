<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'subscribed']);
    }

    public function index(Request $request)
    {
        // TODO: handle launcher and client notifications
        return $request->user()->unreadNotifications;
    }

    public function update(Request $request, $id)
    {
        // TODO: handle launcher and client notifications
        DB::table('notifications')
            ->where('type', 'App\Notifications\ClientNotification')
            ->where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->update([
                'read_at' => now(),
            ]);

        return response()->noContent();
    }
}
