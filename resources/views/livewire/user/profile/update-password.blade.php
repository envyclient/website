<form wire:submit.prevent="submit">
    <x-card title="Account security" subtitle="Update your account password. Please do not reuse a password.">

        {{--Current Password Input--}}
        <x-input.group for="current_password" label="Password">
            <x-input.text wire:model.defer="current_password"
                          id="current_password"
                          type="password"
                          required
            />
        </x-input.group>

        {{--New Passowrd Input--}}
        <x-input.group for="password" label="New Password" class="mt-4">
            <x-input.text wire:model.defer="password"
                          id="password"
                          type="password"
                          required
            />
        </x-input.group>

        {{--Password Confirmation Input--}}
        <x-input.group for="password_confirmation" label="Password Confirmation" class="mt-4">
            <x-input.text wire:model.defer="password_confirmation"
                          id="password_confirmation"
                          type="password"
                          required
            />
        </x-input.group>

        {{--Footer--}}
        <x-slot name="footer">
            <x-small-notify class="mr-2"/>
            <a href="{{ route('password.request') }}">
                <x-button.danger>
                    Forget Password
                </x-button.danger>
            </a>
            <x-button.primary type="submit">
                Save
            </x-button.primary>
        </x-slot>
    </x-card>
</form>

