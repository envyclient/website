<?php

namespace App\Http\Controllers\API;

use App\Download;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Download::all('id', 'name');
    }

    public function show(int $id)
    {
        return Storage::download(
            Download::FILES_DIRECTORY . '/' . Download::findOrFail($id)->file
        );
    }
}
