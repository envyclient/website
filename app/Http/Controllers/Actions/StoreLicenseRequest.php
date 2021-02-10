<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\LicenseRequest;
use Illuminate\Http\Request;

class StoreLicenseRequest extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Request $request)
    {
        $data = $this->validate($request, [
            'channel' => 'required|string|unique:license_requests,channel',
        ]);

        $user = $request->user();

        if ($user->hasLicenseRequest()) {
            return back()->with('error', 'You may only submit one request.');
        }

        LicenseRequest::create([
            'user_id' => $user->id,
            'channel' => $data['channel'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request submitted.');
    }
}
