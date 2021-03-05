<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LauncherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed']);
        $this->middleware('admin')->only('store');
    }

    public function download()
    {
        return Storage::disk('local')->download('launcher/envy.exe');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'launcher-version' => 'required|string|numeric',
            'launcher' => 'required|file|max:3072',
        ]);

        // updating the version file
        Storage::put('launcher/latest.json', json_encode([
            'version' => (double)$request->input('launcher-version'),
        ]));

        // storing the launcher
        Storage::disk('local')->putFileAs(
            'launcher/',
            $request->file('launcher'),
            'envy.exe'
        );

        return back()->with('success', 'Launcher uploaded.');
    }
}
