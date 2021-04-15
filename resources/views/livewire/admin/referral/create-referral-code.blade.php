<form wire:submit.prevent="submit" class="mt-4">

    <x-card title="Create Referral Code" subtitle="Create a referral code for a user">

        {{-- User Input--}}
        <x-input.group for="user" label="User">
            <x-input.text wire:model.defer="user" id="user"/>
        </x-input.group>

        {{-- Referral Code Input--}}
        <x-input.group for="code" label="Code" class="mt-3">
            <x-input.text wire:model.defer="code" id="code"/>
        </x-input.group>

        <x-slot name="footer">
            <x-small-notify/>
            <x-button.primary type="submit">Create</x-button.primary>
        </x-slot>

    </x-card>

</form>
