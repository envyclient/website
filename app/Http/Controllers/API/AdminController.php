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

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'api-admin']);
    }

    public function users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'type' => 'nullable|string|in:all,banned',
            'page' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::with(['subscription', 'wallet'])
            ->name($request->name);

        if ($request->type === 'banned') {
            $user->where('banned', true);
        }

        return $user->paginate(20);
    }

    public function credits(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $user->deposit($amount, 'deposit', ['admin_id' => auth()->id(), 'description' => "Admin {$request->user()->name} deposited $amount credits into your account."]);

        return response()->json([
            'message' => '200 OK'
        ]);
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        if ($user->hasSubscription()) {
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
            'message' => '200 OK'
        ]);
    }
}
