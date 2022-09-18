<form wire:submit.prevent="submit">
    <x-card title="Update Assets" subtitle="Update the launcher assets.">

        {{--Assets Input--}}
        <x-input.group for="assets" label="Assets" class="mt-4">
            <x-input.filepond wire:model="assets"
                              id="assets"
                              maxFileSize="5120KB"
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
