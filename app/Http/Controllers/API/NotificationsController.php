<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    public function update(Request $request, $id)
    {
        $request->user()
            ->notifications()
            ->where('id', $id)
            ->markAsRead();

        return response()->noContent();
    }
}
