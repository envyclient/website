<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
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
            'version' => 'required|file|max:40000'
        ]);

        $file = $request->file('version');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs(Version::FILES_DIRECTORY, $file, $fileName);

        Version::create([
            'name' => $request->name,
            'beta' => $request->beta !== null,
            'file' => $fileName,
        ]);

        return back()->with('success', 'Version upload.');
    }
}
