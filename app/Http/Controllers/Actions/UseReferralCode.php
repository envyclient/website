<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UseReferralCode extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Request $request): RedirectResponse
    {
        $data = $this->validate($request, [
            'referral-code' => 'required|string|exists:referral_codes,code',
        ]);

        $user = $request->user();

        if ($user->referral_code_id !== null) {
            return back()->with('error', 'You have already used a referral code.');
        }

        $code = ReferralCode::where('code', $data['referral-code'])
            ->first();

        $user->fill([
            'referral_code_id' => $code->id
        ])->save();

        return back()->with('success', 'Used referral code.');
    }
}
