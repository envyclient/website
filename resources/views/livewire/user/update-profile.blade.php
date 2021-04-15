<form wire:submit.prevent="submit">
    <x-card title="Account details"
            subtitle="Update your account information. Please note that if you change your email you will need to verify it.">

        {{--Name Input--}}
        <x-input.group for="name" label="Name">
            <x-input.text wire:model.defer="name" id="name"/>
        </x-input.group>

        {{--Email Input--}}
        <x-input.group for="email" label="Email" class="mt-4">
            <x-input.text wire:model.defer="email" id="email" type="email"/>
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <x-button.primary type="submit">Save</x-button.primary>
        </x-slot>

    </x-card>
</form>

