<?php

namespace App\Http\Livewire\Admin\Version;

use App\Models\Version;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $changelog = '';
    public bool $beta = false;
    public $files = [];

    protected array $rules = [
        'name' => 'bail|required|string|max:30|unique:versions',
        'changelog' => 'required|string',
        'beta' => 'nullable',
        'files.*' => 'bail|required|file|max:25000|min:2',
    ];

    public function render()
    {
        return view('livewire.admin.version.upload-version');
    }

    public function submit()
    {
        $this->validate();

        // store the version & assets
        $path = 'versions/' . Str::uuid();
        foreach ($this->files as $file) {
            if ($file->extension() === 'jar') {
                $file->storeAs($path, 'assets.jar');
            } else {
                $file->storeAs($path, 'version.exe');
            }
        }

        // create the version
        Version::create([
            'name' => $this->name,
            'beta' => $this->beta,
            'version' => "$path/version.exe",
            'assets' => "$path/assets.jar",
            'changelog' => $this->changelog,
        ]);

        $this->done();
    }

    private function done()
    {
        // reset inputs
        $this->name = '';
        $this->changelog = '';
        $this->beta = false;
        $this->files = [];

        // clear the filepond file
        $this->resetFilePond();

        $this->smallNotify('Version upload.');

        // tell the versions table to update
        $this->emit('UPDATE_VERSIONS');
    }
}
