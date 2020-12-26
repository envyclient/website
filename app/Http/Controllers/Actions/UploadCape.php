<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadCape extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed']);
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'cape' => 'required|file|image|mimes:png,jpeg,jpg|dimensions:width=92,height=44|max:1024',
        ]);

        $file = $request->file('cape');
        $fileName = md5(auth()->user()->email) . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('capes', $file, $fileName);

        auth()->user()->fill([
            'cape' => asset("storage/capes/$fileName"),
        ])->save();

        return back()->with('success', 'Cape uploaded.');
    }
}
