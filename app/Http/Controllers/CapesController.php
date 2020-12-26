<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CapesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'subscribed']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'cape' => 'required|file|image|mimes:png|dimensions:width=2048,height=1024|max:1024',
        ]);

        $file = $request->file('cape');
        $fileName = md5($request->user()->email) . '.png';
        Storage::disk('public')->putFileAs('capes', $file, $fileName);

        $request->user()->fill([
            'cape' => asset("storage/capes/$fileName"),
        ])->save();

        return back()->with('success', 'Cape uploaded.');
    }

    public function destroy(Request $request)
    {
        // deleting the actual cape
        $hash = md5($request->user()->email);
        Storage::disk('public')->delete("capes/$hash.png");

        $request->user()->fill([
            'cape' => asset('assets/capes/default.png'),
        ])->save();

        return back()->with('success', 'Cape reset.');
    }
}
