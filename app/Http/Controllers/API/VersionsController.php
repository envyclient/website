<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Version as VersionResource;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return VersionResource::collection(
            $user->hasBetaAccess() ? Version::all() : Version::where('beta', false)->get()
        );
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $version = Version::findOrFail($id);

        $now = now();
        DB::table('user_downloads')->insert([
            [
                'user_id' => $user->id,
                'version_id' => $version->id,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        return Storage::download("versions/$version->file");
    }
}
