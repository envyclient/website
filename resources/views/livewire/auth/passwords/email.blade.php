@section('title', 'Reset Password')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Reset password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">

            @if (session('status'))
                <div class="rounded-md bg-green-100 p-4 mb-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="ml-3">
                            <p class="text-sm leading-5 font-medium text-green-800">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="submit">

                <x-input.group for="email" label="Email">
                    <x-input.text wire:model="email"
                                  id="email"
                                  type="email"
                                  autocomplete="email"
                                  autofocus
                                  required/>
                </x-input.group>

                <div class="mt-4">
                    <x-auth.button type="submit" wire:loading.attr="disabled">
                        Send password reset link
                    </x-auth.button>
                </div>

            </form>

        </div>
    </div>
</div>
