<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    // get all versions
    public function index(Request $request)
    {
        $user = $request->user();
        $versions = Version::orderBy('created_at', 'desc');
        if (!$user->hasBetaAccess()) {
            $versions->where('beta', false);
        }
        return $versions->get(['id', 'name', 'main_class', 'beta', 'changelog']);
    }

    // download a version
    public function show(Version $version)
    {
        // get the versions folder path
        $hash = md5($version->id);

        $now = now();
        DB::table('user_downloads')->insert([
            [
                'user_id' => auth()->id(),
                'version_id' => $version->id,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        return Storage::download("versions/$hash.jar");
    }
}
