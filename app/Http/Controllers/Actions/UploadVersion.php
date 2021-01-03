<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Version;
use App\Notifications\ClientNotification;
use App\Notifications\VersionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UploadVersion extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30|unique:versions',
            'beta' => 'nullable',
            'version' => 'required|file|max:40000',
            'assets' => 'required|file|max:10000',
            'changelog' => 'required|string',
        ]);

        // store version & assets
        $path = 'versions/' . bin2hex(openssl_random_pseudo_bytes(10));
        Storage::disk('local')->putFileAs($path, $request->file('version'), 'version.exe');
        Storage::disk('local')->putFileAs($path, $request->file('assets'), 'assets.jar');

        Version::create([
            'name' => $request->name,
            'beta' => $request->has('beta'),
            'version' => "$path/version.exe",
            'assets' => "$path/assets.jar",
            'changelog' => $request->changelog,
        ]);

        // send the notification to all users
        Notification::send(
            User::all(),
            new ClientNotification('info', "$request->name has been released."),
        );

        return back()->with('success', 'Version upload.');
    }
}
