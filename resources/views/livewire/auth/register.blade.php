@section('title', 'Register')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <a href="{{ route('index') }}">
            <x-logo class="w-auto h-16 mx-auto"/>
        </a>
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Create a new account
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('login') }}"
               class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                sign in to your account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="submit">

                <x-input.group for="name" label="Name">
                    <x-input.text wire:model="name"
                                  id="name"
                                  type="text"
                                  autocomplete="username"
                                  autofocus
                                  required/>
                </x-input.group>

                <x-input.group for="email" label="Email address" class="mt-4">
                    <x-input.text wire:model="email"
                                  id="email"
                                  type="email"
                                  autocomplete="email"
                                  required/>
                </x-input.group>

                <x-input.group for="password" label="Password" class="mt-4">
                    <x-input.text wire:model="password"
                                  id="password"
                                  type="password"
                                  autocomplete="new-password"
                                  required/>
                </x-input.group>

                <x-input.group for="password_confirmation" label="Confirm Password" class="mt-4">
                    <x-input.text wire:model="passwordConfirmation"
                                  id="password_confirmation"
                                  type="password"
                                  required/>
                </x-input.group>

                <div class="mt-4">
                    <x-auth.button type="submit" wire:loading.attr="disabled">
                        Register
                    </x-auth.button>
                </div>
            </form>
        </div>
    </div>
</div>
