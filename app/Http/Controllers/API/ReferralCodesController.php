<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReferralCode as ReferralCodeResource;
use App\ReferralCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferralCodesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'api-admin']);
    }

    public function index()
    {
        return ReferralCodeResource::collection(
            ReferralCode::with('user')
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|exists:users,name',
            'code' => 'required|string|max:15|unique:referral_codes,code'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        $user = User::where('name', $request->username)->firstOrFail();

        $referralCode = new ReferralCode();
        $referralCode->user_id = $user->id;
        $referralCode->code = $request->code;
        $referralCode->save();

        return response()->json([
            'message' => '201 Created',
            'id' => $referralCode->id
        ], 201);
    }
}
