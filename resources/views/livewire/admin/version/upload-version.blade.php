<form wire:submit.prevent="submit">
    <x-card title="Upload Version" subtitle="Upload a new version to the server.">

        {{--Name Input--}}
        <x-input.group for="name" label="Name">
            <x-input.text wire:model.defer="name" id="name"/>
        </x-input.group>

        {{--Changelog Input--}}
        <x-input.group for="changelog" label="Changelog" class="mt-4">
            <x-input.textarea wire:model.defer="changelog"
                              id="changelog"
                              rows="3"
            />
        </x-input.group>

        {{--Beta Input--}}
        <x-input.group for="beta" label="Beta" class="mt-4">
            <x-input.toggle wire:model.defer="beta" id="beta"/>
        </x-input.group>

        {{--Version Input--}}
        <x-input.group for="version" label="Version" class="mt-4">
            <x-filepond wire:model="version" id="version" required/>
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit" wire:loading.disabled>Upload</x-button.primary>
        </x-slot>

    </x-card>
</form>

