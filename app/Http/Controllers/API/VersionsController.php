<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Version as VersionResource;
use App\Version;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return VersionResource::collection(
            Version::all()
        );
    }

    public function show(int $id)
    {
        return Storage::disk('minio')->download(
            Version::FILES_DIRECTORY . '/' . Version::findOrFail($id)->file
        );
    }
}
