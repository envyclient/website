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
            'version' => 'required|file|max:40000',
            'changelog' => 'required|string',
        ]);

        $file = $request->file('version');
        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->putFileAs('versions', $file, $fileName);

        Version::create([
            'name' => $request->name,
            'beta' => $request->has('beta'),
            'file' => $fileName,
            'changelog' => $request->changelog,
        ]);

        return back()->with('success', 'Version upload.');
    }
}
