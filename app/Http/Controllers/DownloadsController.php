<?php

namespace App\Http\Controllers;

use App\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'admin']);
    }

    public function create()
    {
        return view('downloads.create');
    }

    public function store(Request $request)
    {
        // TODO: increase post & file size
        $this->validate($request, [
            'name' => 'required|string|max:30|unique:downloads',
            'file' => 'required|file|max:40000|unique:downloads'
        ]);

        $file = $request->file('file');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::disk('minio')->putFileAs(Download::FILES_DIRECTORY, $file, $fileName);

        $download = new Download();
        $download->name = $request->name;
        $download->file = $fileName;
        $download->save();

        return redirect('/admin')->with('success', 'Download created.');
    }
}
