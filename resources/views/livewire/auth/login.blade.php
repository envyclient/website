@section('title', 'Login')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <a href="{{ route('index') }}">
            <x-logo class="w-auto h-16 mx-auto"/>
        </a>
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('register') }}"
               class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                create a new account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="submit">

                <x-input.group for="email" label="Email">
                    <x-input.text wire:model="email"
                                  id="email"
                                  type="email"
                                  autocomplete="email"
                                  autofocus
                                  required/>
                </x-input.group>

                <x-input.group for="password" label="Password" class="mt-4">
                    <x-input.text wire:model="password"
                                  id="password"
                                  type="password"
                                  autocomplete="current-password"
                                  required/>
                </x-input.group>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input wire:model="remember"
                               class="form-checkbox w-4 h-4 text-green-600 transition duration-150 ease-in-out focus:outline-none focus:ring-gray-900 focus:border-gray-900"
                               id="remember"
                               type="checkbox">
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            Remember
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}"
                           class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div class="mt-4">
                    <x-auth.button type="submit" wire:loading.attr="disabled">
                        Login
                    </x-auth.button>
                </div>

            </form>
        </div>
    </div>
</div>
