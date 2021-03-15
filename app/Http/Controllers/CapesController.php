<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CapesController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'cape' => 'required|file|image|mimes:png|dimensions:width=2048,height=1024|max:1024',
        ]);

        $file = $request->file('cape');
        $fileName = md5($request->user()->email) . '.png';
        $path = Storage::cloud()->putFileAs('capes', $file, $fileName, 'public');

        $request->user()->update([
            'cape' => Storage::cloud()->url($path),
        ]);

        return back()->with('success', 'Cape uploaded.');
    }

    public function destroy(Request $request)
    {
        // deleting the actual cape
        $hash = md5($request->user()->email);
        Storage::cloud()->delete("capes/$hash.png");

        $request->user()->update([
            'cape' => asset('assets/capes/default.png'),
        ]);

        return back()->with('success', 'Cape reset.');
    }
}
