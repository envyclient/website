<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ClientNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string|in:info,warning',
            'notification' => 'required|string',
        ]);

        // send the notification to all users
        Notification::send(
            User::all(),
            new ClientNotification($request->type, $request->notification),
        );

        return back()->with('success', 'Notification created.');
    }

    public function destroy(string $id)
    {
        $version = DB::table('notifications')
            ->where('id', $id)
            ->first();

        DB::table('notifications')
            ->where('data', $version->data)
            ->delete();

        return back()->with('success', 'Notification deleted.');
    }
}
