<?php

namespace App\Http\Controllers\Versions\Actions;

use App\Http\Controllers\Versions\VersionsController;
use App\Http\Resources\Version as VersionResource;
use App\Models\Version;
use Illuminate\Http\Request;

class ShowAllVersions extends VersionsController
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        return VersionResource::collection(
            $user->hasBetaAccess() ? Version::all() : Version::where('beta', false)->get()
        );
    }
}
