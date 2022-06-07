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
        $latest = null;
        if (Storage::cloud()->exists('manifest.json')) {
            $latest = json_decode(
                Storage::cloud()->get('manifest.json')
            )->loader->version ?? null;
        }
        return view('livewire.admin.upload-loader', compact('latest'));
    }

    public function submit()
    {
        $this->validate();

        $data = json_decode(
            Storage::cloud()->get('manifest.json'),
            true
        );

        $data['loader'] = [
            'version' => floatval($this->version),
            'size' => $this->loader->getSize(),
            'checksum' => sha1_file($this->loader->getRealPath()),
            'created_at' => now(),
        ];

        Storage::cloud()->put('manifest.json', json_encode($data));

        $this->loader->storeAs('', 'loader.exe', 's3');

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
