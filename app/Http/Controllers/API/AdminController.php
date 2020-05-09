<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Subscription;
use App\Transaction;
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

    public function usersStats(Request $request)
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
        $statsArray['month'] = $dataArray->filter(function ($value, $key) {
            return $value->created_at >= Carbon::now()->firstOfMonth() && $value->created_at <= Carbon::now()->lastOfMonth();
        })->count();

        $latestUser = $dataArray->last();
        $statsArray['latest'] = [
            'name' => $request->type === 'total' ? $latestUser->name : $latestUser->user->name,
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

    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $date = Carbon::today();
        switch ($request->filter) {
            case 'yesterday':
            {
                $date = Carbon::yesterday();
                break;
            }
            case 'week':
            {
                $date = Carbon::now()->startOfWeek();
                break;
            }
            case 'month':
            {
                $date = Carbon::now()->startOfMonth();
                break;
            }
            case 'lifetime':
            {
                $date = Carbon::now()->startOfDecade();
                break;
            }
        }

        return Transaction::with('wallet.user')
            ->where('type', 'deposit')
            ->whereBetween('created_at', [$date, Carbon::today()])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function transactionsStats(Request $request)
    {
        // TODO :: shit broken

        $stats['total'] = Transaction::where('type', 'deposit')->sum('amount');

        $stats['today'] = Transaction::where('type', 'deposit')
            ->whereDate('created_at', Carbon::today())
                ->sum('amount');

        $stats['week'] = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount');

        $stats['month'] = Transaction::where('type', 'deposit')
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('amount');

        return $stats;
    }
}
