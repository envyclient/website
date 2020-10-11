<?php

namespace App\Http\Livewire;

use App\Version;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class VersionsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('livewire.versions-table')->with([
            'apiToken' => auth()->user()->api_token,
            'versions' => Version::all(),
        ]);
    }

    public function deleteVersion($id)
    {
        $version = Version::findOrFail($id);
        Storage::disk('minio')->delete(Version::FILES_DIRECTORY . '/' . $version->file);
        DB::table('user_downloads')->where('version_id', $version->id)->delete();
        $version->delete();

        $this->resetPage();
    }
}
