<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadLauncher extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        return Storage::disk('local')->download('launcher/envy.exe');
    }
}
