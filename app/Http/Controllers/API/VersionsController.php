<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Version as VersionResource;
use App\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('api-admin')->only('destroy');
    }

    public function index()
    {
        return VersionResource::collection(
            Version::all()
        );
    }

    public function show(int $id)
    {
        $version = Version::findOrFail($id);
        return Storage::disk('minio')->download(Version::FILES_DIRECTORY . '/' . $version->file);
    }

    public function destroy(Request $request, $id)
    {
        $version = Version::findOrFail($id);
        Storage::disk('minio')->delete(Version::FILES_DIRECTORY . '/' . $version->file);
        $version->delete();

        return response()->json([
            'message' => '200 OK'
        ], 200);
    }
}
