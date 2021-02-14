<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\LicenseRequest;
use App\Rules\CountSubs;
use Illuminate\Http\Request;

class StoreLicenseRequest extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'throttle:3,1']);
    }

    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'channel' => ['required', 'string', 'url', 'unique:license_requests,channel', new CountSubs],
        ]);

        $user = $request->user();

        if ($user->hasLicenseRequest()) {
            return back()->with('error', 'You may only submit one request.');
        }

     /*   LicenseRequest::create([
            'user_id' => $user->id,
            'channel' => $data['channel'],
            'status' => 'pending',
        ]);*/

        return back()->with('success', 'Request submitted.');
    }
}
