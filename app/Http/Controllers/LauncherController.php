<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class LauncherController extends Controller
{
    public function download()
    {
        return Storage::disk('local')->download('launcher/envy.exe');
    }
}
