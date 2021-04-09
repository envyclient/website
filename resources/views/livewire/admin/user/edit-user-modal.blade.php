<div
    x-data
    @edit-user-modal.window="$wire.call('edit', $event.detail)"
>
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model.defer="edit">
            <x-slot name="title">Edit User</x-slot>

            <x-slot name="content">

                <div class="space-y-2">

                    {{-- Name Input--}}
                    <x-input.group for="name" label="Name">
                        <x-input.text wire:model="user.name" id="name"/>
                    </x-input.group>

                    {{-- HWID Toggle--}}
                    <x-input.group for="hwid" label="HWID">
                        <x-input.toggle wire:model="user.hwid" id="hwid"/>
                    </x-input.group>

                    {{-- Banned Toggle--}}
                    <x-input.group for="banned" label="Banned">
                        <x-input.toggle wire:model="user.banned" id="banned"/>
                    </x-input.group>

                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('edit', false)">Cancel</x-button.secondary>
                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>

