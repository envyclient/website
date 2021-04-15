<form wire:submit.prevent="submit">
    <x-card title="Update Launcher" subtitle="Update the launcher and its version number.">

        {{--Version Input--}}
        <x-input.group for="version" label="Version">
            <x-input.text wire:model.defer="version" id="version"/>
        </x-input.group>

        {{--Launcher Input--}}
        <x-input.group for="launcher" label="Launcher" class="mt-4">
            <x-filepond wire:model="launcher" required/>
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit">Upload</x-button.primary>
        </x-slot>

    </x-card>
</form>
