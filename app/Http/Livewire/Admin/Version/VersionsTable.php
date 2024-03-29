<?php

namespace App\Http\Livewire\Admin\Version;

use App\Models\Version;
use Livewire\Component;
use Livewire\WithPagination;

class VersionsTable extends Component
{
    use WithPagination;

    public bool $edit = false;

    public Version $editVersion;

    protected array $rules = [
        'editVersion.name' => ['required', 'string', 'max:30'],
        'editVersion.main_class' => ['required', 'string', 'max:255'],
        'editVersion.changelog' => ['required', 'string', 'max:65535'],
        'editVersion.beta' => ['nullable', 'bool'],
    ];

    protected $listeners = ['UPDATE_VERSIONS' => '$refresh'];

    // make empty version
    public function mount()
    {
        $this->editVersion = Version::make();
    }

    public function render()
    {
        return view('livewire.admin.version.versions-table', [
            'versions' => Version::orderBy('created_at')->paginate(5),
        ]);
    }

    public function edit(Version $version)
    {
        $this->edit = true;
        $this->editVersion = $version;
    }

    public function save()
    {
        $this->edit = false;
        $this->editVersion->save();
    }

    public function delete(Version $version)
    {
        $version->delete();
    }
}
