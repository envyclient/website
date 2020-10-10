<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Version as VersionResource;
use App\Version;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin')->only('destroy');
    }

    // used to download JRE, ASSETS, or CLIENT ASSETS
    public function assets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_name' => 'required|string|in:jre.zip,assets.zip,client-assets.jar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '400 Bad Request'
            ], 400);
        }

        return Storage::disk('minio')->download($request->file_name);
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
        Storage::disk('minio')->delete(Version::FILES_DIRECTORY . '/' . $version->file);
        DB::table('user_downloads')->where('version_id', $version->id)->delete();
        $version->delete();

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
