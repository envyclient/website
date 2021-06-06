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
        'launcher' => ['required', 'file', 'max:10240'],
    ];

    public function render()
    {
        $latest = null;
        if (Storage::disk('public')->exists('manifest/launcher.json')) {
            $manifest = Storage::disk('public')->get('manifest/launcher.json');
            $latest = json_decode($manifest)->version;
        }
        return view('livewire.admin.upload-launcher', compact('latest'));
    }

    public function submit()
    {
        $this->validate();

        // generating the manifest file for the launcher
        Storage::disk('public')->put('manifest/launcher.json', json_encode([
            'version' => floatval($this->version),
            'size' => $this->launcher->getSize(),
        ]));

        // storing the launcher
        $this->launcher->storeAs('', 'launcher.exe');

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
