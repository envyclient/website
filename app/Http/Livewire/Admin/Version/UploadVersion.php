<?php

namespace App\Http\Livewire\Admin\Version;

use App\Jobs\EncryptVersionJob;
use App\Models\Version;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $main = '';

    public string $changelog = '';

    public bool $beta = false;

    public $version;

    protected array $rules = [
        'name' => ['required', 'string', 'max:30', 'unique:versions'],
        'changelog' => ['required', 'string', 'max:65535'],
        'main' => ['required', 'string', 'max:255'],
        'beta' => ['nullable', 'bool'],
        'version' => ['required', 'file', 'max:25000'],
    ];

    public function render()
    {
        return view('livewire.admin.version.upload-version');
    }

    public function submit()
    {
        $this->validate();

        // create the version
        $version = Version::create([
            'name' => $this->name,
            'beta' => $this->beta,
            'changelog' => $this->changelog,
            'main_class' => $this->main,
            'iv' => bin2hex(random_bytes(16)),
        ]);

        // store the version
        $this->version->storeAs('versions', "$version->hash.jar");

        // dispatch the job to encrypt the version
        EncryptVersionJob::dispatch($version);

        $this->done();
    }

    private function done()
    {
        // reset inputs
        $this->name = '';
        $this->main = '';
        $this->changelog = '';
        $this->beta = false;
        $this->version = null;

        // reset the easymde text
        $this->resetEasyMDE();

        // clear the filepond file
        $this->resetFilePond();

        $this->smallNotify('Version upload.');

        // tell the versions table to update
        $this->emit('UPDATE_VERSIONS');
    }
}
