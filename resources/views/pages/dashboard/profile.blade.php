@extends('layouts.dash')

@section('title', 'Profile')

@section('content')

    {{--Update Profile--}}
    <section>
        @livewire('user.profile.update-profile')
    </section>

    {{--Update Password--}}
    <section class="mt-4">
        @livewire('user.profile.update-password')
    </section>

    {{--Delete Account--}}
    <section
        class="mt-4"
        x-data="{
                show: false,
                focusables() {
                    // All focusable element types...
                    let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
                    return [...$root.querySelectorAll(selector)]
                        // All non-disabled elements...
                        .filter(el => ! el.hasAttribute('disabled'))
                },
                firstFocusable() { return this.focusables()[0] },
                lastFocusable() { return this.focusables().slice(-1)[0] },
                nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
                prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
                nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
                prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
                autofocus() { let focusable = $root.querySelector('[autofocus]'); if (focusable) focusable.focus() }
        }"
        x-init="$watch('show', value => value && setTimeout(autofocus, 50))"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
        x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    >

        <x-card title="Delete your account" subtitle="Once you delete your account all your account data will be gone.">
            <button type="button"
                    @click="show = true"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                Delete account
            </button>
        </x-card>

        <div x-show="show"
             class="fixed z-10 inset-0 overflow-y-auto"
             style="display: none;"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <!-- Background overlay, show/hide based on modal state. -->
                <div
                    x-show="show"
                    class="fixed inset-0 transform transition-all"
                    x-on:click="show = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel, show/hide based on modal state. -->
                <div
                    x-show="show"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <!-- Heroicon name: outline/exclamation -->
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete account
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        By deleting your account you will lose access to your account. Are you sure you
                                        want to proceed?
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('users.delete') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-button.danger type="submit">
                                Delete
                            </x-button.danger>
                        </form>
                        <x-button.secondary type="button" @click="show = false" class="mr-2">
                            Cancel
                        </x-button.secondary>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
