<?php

namespace App\Http\Livewire\Version;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Version extends Component
{
    public int $index;
    public \App\Models\Version $version;
    public bool $editMode = false;

    protected array $rules = [
        'version.name' => 'required|string|max:30',
        'version.beta' => 'nullable|bool',
    ];

    public function mount(int $index, \App\Models\Version $version)
    {
        $this->index = $index;
        $this->version = $version;
    }

    public function render()
    {
        return view('livewire.version.version');
    }

    public function save(): void
    {
        $this->validate();
        $this->version->save();
        $this->editMode = false;
    }

    public function delete(): void
    {
        // delete the directory in which the files are held
        $directory = explode('/', $this->version->version)[1];
        Storage::deleteDirectory("versions/$directory");

        // deleting the version downloads
        /*DB::table('user_downloads')
            ->where('version_id', $this->version->id)
            ->delete();*/

        // deleting the row from the table
        $this->version->delete();

        $this->emit('UPDATE_VERSIONS');
    }
}
