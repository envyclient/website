<?php

namespace App\Http\Livewire;

use App\Models\Version;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public $name;
    public $beta = false;
    public $file;

    protected $rules = [
        'name' => 'required|string|max:30|unique:versions',
        'beta' => 'nullable',
        'file' => 'required|file|max:40000'
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
        Storage::putFileAs(Version::FILES_DIRECTORY, $this->file, $fileName);

        Version::create([
            'name' => $this->name,
            'beta' => $this->beta === true,
            'file' => $fileName,
        ]);

        $this->emit('VERSION_UPLOADED');
    }
}
