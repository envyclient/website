<form wire:submit.prevent="submit">
    <x-card title="Upload Libraries" subtitle="Upload libraries required by the client.">

        {{--Version Input--}}
        <x-input.group for="libraries" label="Libraries" class="mt-4">
            <x-input.filepond wire:model="libraries" id="version" multiple required/>
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit" wire:loading.disabled>Upload</x-button.primary>
        </x-slot>

    </x-card>
</form>

