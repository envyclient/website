<?php

namespace App\Http\Livewire\Admin\Version;

use App\Models\Version;
use Livewire\Component;
use Livewire\WithPagination;

class VersionsTable extends Component
{
    use WithPagination;

    public bool $edit;
    public Version $editVersion;

    protected array $rules = [
        'editVersion.name' => ['required', 'string', 'max:30'],
        'editVersion.beta' => ['nullable', 'bool'],
        'editVersion.changelog' => ['required', 'string'],
    ];

    protected array $listeners = ['UPDATE_VERSIONS' => '$refresh'];

    public function edit(Version $version)
    {
        $this->edit = true;
        $this->editVersion = $version;
    }

    public function save()
    {
        dd($this->editVersion->name);

        $this->edit = false;
        $this->editVersion->save();
    }

    public function render()
    {
        return view('livewire.admin.version.versions-table', [
            'versions' => Version::orderBy('created_at')->paginate(5),
        ]);
    }
}
