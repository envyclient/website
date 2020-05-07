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
        $this->middleware('auth:api');
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
}
