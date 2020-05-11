<?php

namespace App\Http\Controllers;

use App\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'forbid-banned-user', 'admin']);
    }

    public function create()
    {
        return view('downloads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30',
            'file' => 'required|file|max:40000'
        ]);

        $file = $request->file('file');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs(Download::FILES_DIRECTORY, $file, $fileName);

        $download = new Download();
        $download->name = $request->name;
        $download->file = $fileName;
        $download->save();

        return back()->with('success', 'Download created.');
    }
}
