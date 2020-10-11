<?php

namespace App\Http\Controllers\API;

use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Config;
use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use App\Version;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin']);
    }

    public function users(request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'nullable|string',
            'type' => 'nullable|string|in:all,banned',
            'page' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 bad request'
            ], 400);
        }

        $user = user::with(['subscription'])
            ->name($request->name);

        if ($request->type === 'banned') {
            $user->where('banned', true);
        }

        return $user->paginate(20);
    }

    public function ban($id)
    {
        $user = user::findorfail($id);
        if ($user->hassubscription()) {
            $user->subscription->fill([
                'renew' => false
            ])->save();
        }

        $banned = true;
        if ($user->banned) {
            $banned = false;
        }

        $user->fill([
            'banned' => $banned
        ])->save();

        return response()->json([
            'message' => '200 ok'
        ]);
    }
}
