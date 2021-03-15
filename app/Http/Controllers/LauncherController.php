<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LauncherController extends Controller
{
    public function download()
    {
        return Storage::cloud()->download('launcher/envy.exe');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'launcher-version' => 'required|string|numeric',
            'launcher' => 'required|file|max:3072',
        ]);

        // updating the version file
        Storage::cloud()->put('launcher/latest.json', json_encode([
            'version' => floatval($request->input('launcher-version')),
        ]));

        // storing the launcher
        Storage::cloud()->putFileAs(
            'launcher/',
            $request->file('launcher'),
            'envy.exe'
        );

        return back()->with('success', 'Launcher uploaded.');
    }
}
