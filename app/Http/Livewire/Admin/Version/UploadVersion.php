<?php

namespace App\Http\Livewire\Admin\Version;

use App\Models\Version;
use Livewire\Component;
use Livewire\WithFileUploads;
use SoareCostin\FileVault\Facades\FileVault;

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
        'changelog' => ['required', 'string'],
        'main' => ['required', 'string', 'max:255'],
        'beta' => ['nullable'],
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
            'main_class' => $this->main,
            'changelog' => $this->changelog,
            'beta' => $this->beta,
        ]);

        // generate hash for version file
        $hash = md5($version->id);

        // store the version
        $this->version->storeAs("versions", "$hash.jar");

        // encrypt the version
        FileVault::encrypt("versions/$hash.jar");

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

        // clear the filepond file
        $this->resetFilePond();

        $this->smallNotify('Version upload.');

        // tell the versions table to update
        $this->emit('UPDATE_VERSIONS');
    }
}
