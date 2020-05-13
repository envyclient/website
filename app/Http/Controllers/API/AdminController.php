<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction as TransactionResource;
use App\Subscription;
use App\User;
use Carbon\Carbon;
use Depsimon\Wallet\Transaction;
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
            'page' => 'required|integer'
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
            'date' => $latestUser->created_at->diffForHumans()
        ];

        return $statsArray;
    }

    public function credits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'amount' => 'required|integer'
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
            'user_id' => 'required|integer',
            'ban_reason' => 'required|integer'
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
            'user_id' => 'required|integer'
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
                return TransactionResource::collection(
                    Transaction::with('wallet.user')
                        ->where([
                            ['type', '=', 'deposit'],
                            ['created_at', '=', Carbon::yesterday()]
                        ])
                        ->orderBy('created_at', 'desc')
                        ->get()
                );
                break;
            }
            case 'week':
            {
                $date = Carbon::now()->subDays(7);
                break;
            }
            case 'month':
            {
                $date = Carbon::now()->subDays(30);
                break;
            }
            case 'lifetime':
            {
                return TransactionResource::collection(
                    Transaction::with('wallet.user')
                        ->orderBy('created_at', 'desc')
                        ->get()
                );
                break;
            }
        }

        return TransactionResource::collection(
            Transaction::with('wallet.user')
                ->where('type', 'deposit')
                ->whereBetween('created_at', [$date, Carbon::now()])
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function transactionsStats(Request $request)
    {
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
