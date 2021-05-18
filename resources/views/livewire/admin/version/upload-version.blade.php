<form wire:submit.prevent="submit">
    <x-card title="Upload Version" subtitle="Upload a new version to the server.">

        {{--Name Input--}}
        <x-input.group for="name" label="Name">
            <x-input.text wire:model.defer="name" id="name" required/>
        </x-input.group>

        {{--Main Class Input--}}
        <x-input.group for="main" label="Main Class" class="mt-4">
            <x-input.text wire:model.defer="main" id="main" required/>
        </x-input.group>

        {{--Changelog Input--}}
        <x-input.group for="changelog" label="Changelog" class="mt-4">
            <x-input.easymde wire:model.defer="changelog" id="changelog" maxlength="65535"/>
        </x-input.group>

        {{--Beta Input--}}
        <x-input.group for="beta" label="Beta">
            <x-input.toggle wire:model.defer="beta" id="beta" required/>
        </x-input.group>

        {{--Version Input--}}
        <x-input.group for="version" label="Version" class="mt-4">
            <x-input.filepond wire:model="version" id="version"/>
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit"
                              id="upload-button"
                              wire:loading.disabled>
                Upload
            </x-button.primary>
        </x-slot>

    </x-card>
</form>

