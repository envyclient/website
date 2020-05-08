<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'page' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return User::with(['subscription', 'wallet'])
            ->name($request->name)
            ->paginate(20);
    }

    public function totalUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:total,premium'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $dataArray = $request->type === 'total' ? User::all() : Subscription::all();
        $statsArray['total'] = $dataArray->count();
        $statsArray['today'] = $dataArray->filter(function ($value, $key) {
            return $value->created_at == Carbon::today();
        })->count();
        $statsArray['week'] = $dataArray->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->startOfWeek() && $value->created_at <= Carbon::now()->endOfWeek();
        })->count();
        $statsArray['month'] = $dataArray->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->firstOfMonth() && $value->created_at <= Carbon::now()->lastOfMonth();
        })->count();

        $latestUser = $dataArray->last();
        $statsArray['latest'] = [
            'name' => $latestUser->name,
            'time' => $latestUser->created_at->diffForHumans()
        ];

        return $statsArray;
    }

    public function credits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'amount' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::findOrFail($request->user_id);
        $amount = $request->amount;
        $user->deposit($amount, 'deposit', ['admin_id' => auth()->id(), 'description' => "Admin {$request->user()->name} deposited $amount credits into your account."]);

        return response()->json([
            'message' => '200 OK'
        ]);
    }

    public function ban(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int',
            'ban_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::findOrFail($request->user_id);

        if ($user->hasSubscription()) {
            $user->subscription->fill([
                'renew' => false
            ])->save();
        }

        $user->fill([
            'ban_reason' => $request->ban_reason
        ])->save();

        return response()->json([
            'message' => '200 OK'
        ]);
    }

    public function unBan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::findOrFail($request->user_id);
        $user->fill([
            'ban_reason' => null
        ])->save();

        return response()->json([
            'message' => '200 OK'
        ]);
    }
}
