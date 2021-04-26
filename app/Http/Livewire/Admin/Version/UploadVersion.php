<?php

namespace App\Http\Livewire\Admin\Version;

use App\Jobs\ProcessVersion;
use App\Models\Version;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadVersion extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $changelog = '';
    public bool $beta = false;
    public $version;

    protected array $rules = [
        'name' => ['required', 'string', 'max:30', 'unique:versions'],
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

        // store the version & assets
        $path = 'versions/' . Str::uuid();
        $this->version->storeAs($path, 'version.jar');

        // create the version
        $version = Version::create([
            'name' => $this->name,
            'beta' => $this->beta,
            'changelog' => $this->changelog,
        ]);

        // dispatch the job
        ProcessVersion::dispatch($version, $path);

        $this->done();
    }

    private function done()
    {
        // reset inputs
        $this->name = '';
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
