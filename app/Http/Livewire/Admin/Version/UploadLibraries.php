<?php

namespace App\Http\Livewire\Admin\Version;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadLibraries extends Component
{
    use WithFileUploads;

    public $libraries = [];

    protected array $rules = [
        'libraries.*' => ['required', 'file', 'max:3072'],
    ];

    public function render()
    {
        return view('livewire.admin.version.upload-libraries');
    }

    public function submit()
    {
        foreach ($this->libraries as $library) {
            dd($library);
            //$photo->store('photos');
        }
    }
}
