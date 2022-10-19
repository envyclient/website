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
        if (Storage::disk('s3_public')->exists('manifest.json')) {
            $latest = json_decode(
                Storage::disk('s3_public')->get('manifest.json')
            )->launcher->version ?? null;
        }
        return view('livewire.admin.upload-launcher', compact('latest'));
    }

    public function submit()
    {
        $this->validate();

        $data = json_decode(
            Storage::disk('s3_public')->get('manifest.json'),
            true
        );

        $data['launcher'] = [
            'version' => floatval($this->version),
            'size' => $this->launcher->getSize(),
            'checksum' => sha1_file($this->launcher->getRealPath()),
            'created_at' => now(),
        ];

        Storage::disk('s3_public')->put('manifest.json', json_encode($data));

        $this->launcher->storeAs('', 'launcher.exe', 's3');

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
