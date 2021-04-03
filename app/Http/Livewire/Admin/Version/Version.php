<?php

namespace App\Http\Livewire\Admin\Version;

use Livewire\Component;

class Version extends Component
{
    public int $index;
    public \App\Models\Version $version;

    public bool $editMode = false;
    public string $name = '';
    public bool $beta = false;
    public string $changelog = '';

    protected array $rules = [
        'name' => 'required|string|max:30',
        'beta' => 'nullable|bool',
        'changelog' => 'required|string',
    ];

    public function mount(int $index, \App\Models\Version $version)
    {
        $this->index = $index;
        $this->version = $version;
    }

    public function render()
    {
        return view('livewire.admin.version.version');
    }

    public function editMode()
    {
        $this->editMode = true;
        $this->name = $this->version->name;
        $this->beta = $this->version->beta;
        $this->changelog = $this->version->changelog;
    }

    public function save(): void
    {
        $this->validate();

        $this->editMode = false;
        $this->version->update([
            'name' => $this->name,
            'beta' => $this->beta,
            'changelog' => $this->changelog
        ]);
    }
}
