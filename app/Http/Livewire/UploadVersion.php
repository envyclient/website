<?php

namespace App\Http\Livewire;

use App\Version;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public $name;
    public $beta;
    public $file;

    protected $rules = [
        'name' => 'required|string|max:30|unique:versions',
        'beta' => 'nullable',
        'file' => 'required|file|max:10'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.upload-version');
    }

    public function save()
    {
        $this->validate();

        $fileName = bin2hex(openssl_random_pseudo_bytes(30)) . '.' . $this->file->getClientOriginalExtension();
        Storage::disk('minio')->putFileAs(Version::FILES_DIRECTORY, $this->file, $fileName);

        $version = new Version();
        $version->name = $this->name;
        if ($this->beta === true) {
            $version->beta = true;
        }
        $version->file = $fileName;
        $version->save();

        $this->emit('VERSION_UPLOADED');
    }
}
