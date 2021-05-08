<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadLoader extends Component
{
    use WithFileUploads;

    public string $version = '';
    public $loader;

    protected array $rules = [
        'version' => ['required', 'string', 'numeric'],
        'loader' => ['required', 'file', 'max:3072'],
    ];

    public function render()
    {
        return view('livewire.admin.upload-loader');
    }

    public function submit()
    {
        $this->validate();

        // generating the manifest file for the loader
        Storage::disk('public')->put('manifest/loader.json', json_encode([
            'version' => floatval($this->version),
            'size' => $this->loader->getSize(),
        ]));

        // storing the loader
        $this->loader->storeAs('', 'loader.exe');

        $this->done();
    }

    private function done()
    {
        $this->version = '';
        $this->loader = null;
        $this->resetFilePond();
        $this->smallNotify('Loader updated.');
    }
}
