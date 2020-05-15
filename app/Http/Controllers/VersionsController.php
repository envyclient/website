<?php

namespace App\Http\Controllers;

use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30|u nique:versions',
            'file' => 'required|file|max:20000|unique:versions'
        ]);

        $file = $request->file('file');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::disk('minio')->putFileAs(Version::FILES_DIRECTORY, $file, $fileName);

        $version = new Version();
        $version->name = $request->name;
        $version->file = $fileName;
        $version->save();

        return redirect('/admin')->with('success', 'Version created.');
    }

}
