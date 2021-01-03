<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'subscribed']);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:launcher,client'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $notifications = DB::table('notifications')
            ->where('notifiable_id', $request->user()->id)
            ->where('read_at', null)
            ->orderBy('created_at', 'desc');

        if ($request->type === 'launcher') {
            $notifications->where('type', 'App\Notifications\LauncherNotification');
        } else {
            $notifications->where('type', 'App\Notifications\ClientNotification');
        }

        return $notifications->get();
    }

    public function update(Request $request, $id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $request->user()->id)
            ->update([
                'read_at' => now(),
            ]);

        return response()->noContent();
    }
}
