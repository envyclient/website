<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadLauncher extends Component
{
    use WithFileUploads;

    public string $version = '';
    public $launcher;

    protected array $rules = [
        'version' => ['required', 'string', 'numeric'],
        'launcher' => ['required', 'file', 'max:3072'],
    ];

    public function render()
    {
        return view('livewire.admin.upload-launcher');
    }

    public function submit()
    {
        $this->validate();

        // updating the version file
        Storage::disk('local')->put('launcher/latest.json', json_encode([
            'version' => floatval($this->version),
        ]));

        // storing the launcher
        Storage::disk('local')->putFileAs('launcher/', $this->launcher, 'envy.exe');

        $this->done();
    }

    private function done()
    {
        $this->version = '';
        $this->launcher = null;
        $this->resetFilePond();
        $this->smallNotify('Launcher updated.');
    }
}
