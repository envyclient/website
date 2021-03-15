<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CapesController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'cape' => 'bail|required|file|image|mimes:png|dimensions:width=2048,height=1024|max:1024',
        ]);

        $file = $request->file('cape');
        $fileName = md5($request->user()->email) . '.png';
        $path = Storage::disk('public')->putFileAs('capes', $file, $fileName);

        $request->user()->update([
            'cape' => Storage::disk('public')->url($path),
        ]);

        return back()->with('success', 'Cape uploaded.');
    }

    public function destroy(Request $request)
    {
        // deleting the actual cape
        $hash = md5($request->user()->email);
        Storage::disk('public')->delete("capes/$hash.png");

        $request->user()->update([
            'cape' => asset('assets/capes/default.png'),
        ]);

        return back()->with('success', 'Cape reset.');
    }
}
