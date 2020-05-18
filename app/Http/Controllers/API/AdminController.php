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

    public function transactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string|in:today,yesterday,week,month,lifetime',
            'type' => 'required|string|in:all,deposit,withdraw'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $startDate = Carbon::today();
        $endDate = Carbon::today();
        switch ($request->date) {
            case 'yesterday':
            {
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            }
            case 'week':
            {
                $startDate = Carbon::today()->subDays(7);
                break;
            }
            case 'month':
            {
                $startDate = Carbon::today()->subDays(30);
                break;
            }
            case 'lifetime':
            {
                $startDate = Carbon::today()->startOfDecade();
                break;
            }
        }

        $transaction = Transaction::with('wallet.user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc');

        switch ($request->type) {
            case 'deposit':
            {
                $transaction = $transaction->where('type', 'deposit');
                break;
            }
            case 'withdraw':
            {
                $transaction = $transaction->where('type', 'withdraw');
                break;
            }
        }

        return TransactionResource::collection(
            $transaction->get()
        );
    }

    public function transactionsStats()
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
