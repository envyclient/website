<?php

namespace App\Http\Controllers;

use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscription']);
        $this->middleware('admin')->only('store');
    }

    public function downloadLauncher()
    {
        // TODO: track what user is download and pack in HWID so we can track leaks
        return Storage::disk('minio')->download('envy-launcher.exe');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30|unique:versions',
            'file' => 'required|file|max:50000|unique:versions'
        ]);

        $file = $request->file('file');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::disk('minio')->putFileAs(Version::FILES_DIRECTORY, $file, $fileName);

        $version = new Version();
        $version->name = $request->name;
        $version->file = $fileName;
        $version->save();

        return back()->with('success', 'Version created.');
    }

}
