<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function index(Request $request)
    {
        $versions = Version::whereNotNull('processed_at')->orderBy('created_at', 'desc');
        if (!$request->user()->hasBetaAccess()) {
            $versions->where('beta', false);
        }
        return $versions->get(['id', 'name', 'beta', 'changelog', 'main_class']);
    }

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

        return Storage::download("versions/$hash.jar.enc");
    }
}
