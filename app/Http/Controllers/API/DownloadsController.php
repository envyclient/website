<?php

namespace App\Http\Controllers\API;

use App\Download;
use App\Http\Controllers\Controller;
use App\Http\Resources\Download as DownloadResource;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('assets', 'jre');
    }

    public function index()
    {
        return DownloadResource::collection(
            Download::all()
        );
    }

    public function show(int $id)
    {
        return Storage::disk('minio')->download(
            Download::FILES_DIRECTORY . '/' . Download::findOrFail($id)->file
        );
    }

    public function assets()
    {
        return Storage::disk('minio')->download('public/assets.zip');
    }

    public function jre()
    {
        return Storage::disk('minio')->download('public/jre.zip');
    }
}
