<?php

namespace App\Http\Livewire\Version;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Version extends Component
{
    public int $index;
    public \App\Models\Version $version;

    public bool $editMode;
    public string $name;
    public bool $beta;

    public function mount(int $index, \App\Models\Version $version)
    {
        $this->index = $index;
        $this->version = $version;
    }

    public function render()
    {
        return view('livewire.version.version');
    }

    public function enableEditMode(): void
    {
        $this->name = $this->version->name;
        $this->beta = $this->version->beta;
        $this->editMode = true;
    }

    public function submit(): void
    {
        $this->validate([
            'name' => 'required|string|max:30',
            'beta' => 'required|boolean',
        ]);

        $this->version->update([
            'name' => $this->name,
            'beta' => $this->beta,
        ]);

        $this->editMode = false;
    }

    public function delete(): void
    {
        // delete the directory in which the files are held
        $directory = explode('/', $this->version->version)[1];
        Storage::deleteDirectory("versions/$directory");

        // deleting the version downloads
        DB::table('user_downloads')
            ->where('version_id', $this->version->id)
            ->delete();

        // deleting the row from the table
        $this->version->delete();

        $this->emit('VERSION_DELETED');
    }
}
