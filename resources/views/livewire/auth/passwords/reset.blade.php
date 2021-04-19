<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Reset password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="resetPassword">
                <input wire:model="token" type="hidden">

                <x-input.group for="email" label="Email">
                    <x-input.text wire:model.defer="email"
                                  id="email"
                                  type="email"
                                  autocomplete="email"
                                  autofocus
                                  required/>
                </x-input.group>

                <x-input.group for="password" label="Password" class="mt-4">
                    <x-input.text wire:model.defer="password"
                                  id="password"
                                  type="password"
                                  autocomplete="new-password"
                                  required/>
                </x-input.group>

                <x-input.group for="password_confirmation" label="Confirm Password" class="mt-4">
                    <x-input.text wire:model.defer="passwordConfirmation"
                                  id="password_confirmation"
                                  type="password"
                                  required/>
                </x-input.group>

                <div class="mt-4">
                    <x-auth.button type="submit" wire:loading.attr="disabled" wire:target="resetPassword">
                        Reset password
                    </x-auth.button>
                </div>
            </form>
        </div>
    </div>
</div>
