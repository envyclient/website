<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadLauncher extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function __invoke(Request $request)
    {
        return Storage::download('envy-launcher.exe');
    }
}
