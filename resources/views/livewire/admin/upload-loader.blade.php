<form wire:submit.prevent="submit">
    <x-card title="Update Loader" subtitle="Update the loader and its version number.">

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
        <x-input.group for="loader" label="Loader" class="mt-4">
            <x-input.filepond wire:model="loader"
                              id="loader"
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
