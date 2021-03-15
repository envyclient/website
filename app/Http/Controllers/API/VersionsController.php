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
        $user = $request->user();
        $versions = Version::orderBy('created_at', 'desc');
        if (!$user->hasBetaAccess()) {
            $versions->where('beta', false);
        }
        return $versions->get();
    }

    public function downloadVersion(Request $request, int $id)
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

        return Storage::disk('local')->download($version->version);
    }

    public function downloadAssets(Request $request, int $id)
    {
        $version = Version::findOrFail($id);
        return Storage::disk('local')->download($version->assets);
    }
}
