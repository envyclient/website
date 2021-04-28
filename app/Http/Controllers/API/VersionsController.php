<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;

class VersionsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $versions = Version::orderBy('created_at', 'desc')->whereNotNull('processed_at');
        if (!$user->hasBetaAccess()) {
            $versions->where('beta', false);
        }
        return $versions->get(['id', 'name', 'beta', 'changelog']);
    }

    public function downloadManifest(Version $version)
    {
        if ($version->processed_at === null) {
            return self::bad();
        }

        // get the versions folder path
        $folder = md5($version->id);

        return Storage::disk('local')->download("versions/$folder/manifest.json");
    }

    public function downloadFile(Version $version, string $hash)
    {
        if ($version->processed_at === null) {
            return self::bad();
        }

        // get the versions folder path
        $folder = md5($version->id);

        // get the file name
        $file = base64_decode($hash);

        return response()->streamDownload(function () use ($folder, $file) {
            FileVault::disk('local')->streamDecrypt("versions/$folder/data/$file");
        });
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
}
