<form wire:submit.prevent="submit">
    <x-card title="Update Launcher" subtitle="Update the launcher and its version number.">

        {{--Version Input--}}
        <x-input.group for="version" label="Version" class="mt-4">
            @isset($latest)
                <x-input.text-prefix wire:model.defer="version" id="version" required>
                    {{ $latest }}
                </x-input.text-prefix>
            @else
                <x-input.text wire:model.defer="version" id="version" required/>
            @endisset
        </x-input.group>

        {{--Launcher Input--}}
        <x-input.group for="launcher" label="Launcher" class="mt-4">
            <x-filepond wire:model="launcher"
                        id="launcher"
                        maxFileSize="3072KB"
                        required
            />
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit">Upload</x-button.primary>
        </x-slot>

    </x-card>
</form>
