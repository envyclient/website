<div
    x-data
    @create-media-request.window="$wire.set('open', true)"
>
    <form wire:submit.prevent="submit">
        <x-modal.dialog wire:model.defer="open">
            <x-slot name="title">New Media Request</x-slot>

            <x-slot name="content">

                {{-- Channel Input--}}
                <x-input.group for="channel" label="Channel">
                    <x-input.text wire:model.defer="channel" id="channel"/>
                </x-input.group>

            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('open', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit" wire:loading.disabled>Submit</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>

