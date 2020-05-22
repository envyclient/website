<?php

namespace App\Http\Controllers\API;

use App\Charts\TransactionsChart;
use App\Charts\UsersChart;
use App\Charts\VersionDownloadsChart;
use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction as TransactionResource;
use App\Subscription;
use App\User;
use App\Version;
use Carbon\Carbon;
use Depsimon\Wallet\Transaction;
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

    public function usersChart()
    {
        $chart = new UsersChart();

        // users
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, User::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Users', 'line', $data)->color('#82c4c3')->fill(false);

        // subscriptions
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, Subscription::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Subscriptions', 'line', $data)->color('#50d890')->fill(false);;

        // configs
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, Config::whereDate('created_at', today()->subDays($days_backwards))->count());
        }
        $chart->dataset('Configs', 'line', $data)->color('#fd5e53')->fill(false);

        return $chart->api();
    }

    public function transactionsChart()
    {
        $data = [];
        for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
            array_push($data, Transaction::where('type', 'deposit')
                ->whereDate('created_at', today()->subDays($days_backwards))
                ->sum('amount')
            );
        }

        $chart = new TransactionsChart();
        $chart->dataset('Transactions', 'line', $data);
        return $chart->api();
    }

    public function versionDownloadsChart()
    {
        $chart = new VersionDownloadsChart();
        foreach (Version::all() as $version) {
            $data = [];
            for ($days_backwards = 7; $days_backwards >= 0; $days_backwards--) {
                array_push($data, DB::table('user_downloads')
                    ->where('version_id', $version->id)
                    ->whereDate('created_at', today()->subDays($days_backwards))
                    ->count()
                );
            }
            $chart->dataset($version->name, 'bar', $data)->backgroundColor(self::randomColor());
        }
        return $chart->api();
    }

    private static function randomColor()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }

}
