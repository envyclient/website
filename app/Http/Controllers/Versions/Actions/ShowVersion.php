<?php

namespace App\Http\Controllers\Versions\Actions;

use App\Http\Controllers\Versions\VersionsController;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShowVersion extends VersionsController
{
    public function __invoke(Request $request, $id)
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

        return Storage::download(Version::FILES_DIRECTORY . "/$version->file");
    }
}
