<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;

class UploadAssets extends Component
{
    use WithFileUploads;

    public $assets;

    protected array $rules = [
        'assets' => ['required', 'file', 'max:5120', 'mimes:zip'],
    ];

    public function render()
    {
        return view('livewire.admin.upload-assets');
    }

    public function submit()
    {
        $this->validate();

        $this->assets->storeAs('', 'assets.zip', 's3_public');

        $this->done();
    }

    private function done()
    {
        $this->assets = null;
        $this->resetFilePond();
        $this->smallNotify('Assets updated.');
    }
}
