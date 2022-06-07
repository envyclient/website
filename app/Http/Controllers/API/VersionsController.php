<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\VersionResource;
use App\Models\Version;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VersionsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return VersionResource::collection(
            Version::query()
                ->whereNotNull('processed_at')
                ->orderBy('created_at', 'desc')
                ->when(!auth()->user()->hasBetaAccess(), fn(Builder $builder) => $builder->where('beta', false))
                ->get()
        );
    }

    public function download(Version $version)
    {
        $now = now();
        DB::table('user_downloads')->insert([
            [
                'user_id' => auth()->id(),
                'version_id' => $version->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        return Storage::cloud()->download('versions/' . md5($version->created_at) . '.jar.enc');
    }
}
