<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UseReferralCode extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $this->validate($request, [
            'referral-code' => ['required', 'string', 'exists:referral_codes,code'],
        ]);

        $user = $request->user();

        // checking if the user has already used a referral code
        if ($user->referral_code_id !== null) {
            return back()->with('error', 'You have already used a referral code.');
        }

        // get the referral code model
        $code = ReferralCode::where('code', $data['referral-code'])->first();

        // update the user to reflect referral code
        $user->update([
            'referral_code_id' => $code->id,
            'referral_code_used_at' => now(),
        ]);

        return back()->with('success', 'Used referral code.');
    }
}
