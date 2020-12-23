<?php

namespace App\Http\Livewire;

use App\Models\Version;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class VersionsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['VERSION_UPLOADED' => '$refresh'];

    public function render()
    {
        return view('livewire.versions-table')->with([
            'apiToken' => auth()->user()->api_token,
            'versions' => Version::orderBy('created_at')->get(),
        ]);
    }

    public function deleteVersion($id)
    {
        $version = Version::findOrFail($id);

        // delete the directory in which the files are held
        $directory = explode('/', $version->version)[1];
        Storage::deleteDirectory("versions/$directory");

        // deleting the version downloads
        DB::table('user_downloads')->where('version_id', $version->id)->delete();

        // deleting the row from the table
        $version->delete();
    }
}
