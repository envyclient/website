<?php

namespace App\Http\Controllers\API\Actions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetLauncherVersion extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }

    public function __invoke(Request $request)
    {
        return Storage::disk('local')->get('launcher/latest.json');
    }
}
