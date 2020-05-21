<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Version as VersionResource;
use App\Version;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('api-admin')->only('destroy');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        return VersionResource::collection(
            $user->hasBetaAccess() ? Version::all() : Version::where('beta', false)
        );
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $version = Version::findOrFail($id);

        DB::table('user_downloads')->insert([
            [
                'user_id' => $user->id,
                'version_id' => $version->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        return Storage::disk('minio')->download(Version::FILES_DIRECTORY . '/' . $version->file);
    }

    public function destroy($id)
    {
        $version = Version::findOrFail($id);
        //Storage::disk('minio')->delete(Version::FILES_DIRECTORY . '/' . $version->file);
        $version->delete();

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
