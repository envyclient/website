<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LauncherController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed'])->except('latest');
        $this->middleware('admin')->only('store');
    }

    public function latest()
    {
        return Storage::disk('local')->get('launcher/latest.json');
    }

    public function download()
    {
        return Storage::disk('local')->download('launcher/envy-launcher.exe');
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
            'envy-launcher.exe'
        );

        return back()->with('success', 'Launcher uploaded.');
    }
}
