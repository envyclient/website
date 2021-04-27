<?php

namespace App\Http\Livewire\Admin\Version;

use App\Jobs\ProcessVersion;
use App\Models\Version;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $changelog = '';
    public string $main = '';
    public bool $beta = false;
    public $version;

    protected array $rules = [
        'name' => ['required', 'string', 'max:30', 'unique:versions'],
        'main' => ['required', 'string', 'max:255'],
        'changelog' => ['required', 'string'],
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
            'changelog' => $this->changelog,
            'beta' => $this->beta,
        ]);

        $folder = md5($version->id);

        // store the version
        $this->version->storeAs("versions/$folder", 'version.jar');

        // dispatch the job
        ProcessVersion::dispatch($version, $folder, $this->main);

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
