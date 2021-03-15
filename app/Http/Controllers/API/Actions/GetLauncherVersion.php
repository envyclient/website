<?php

namespace App\Http\Controllers\API\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetLauncherVersion extends Controller
{
    public function __invoke(Request $request)
    {
        return Storage::cloud()->get('launcher/latest.json');
    }
}
