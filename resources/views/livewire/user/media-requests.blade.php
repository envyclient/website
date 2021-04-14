<section class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">

    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-nowrap">
            <div class="ml-4 mt-2">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Media Applications
                </h3>
            </div>
            <div class="ml-4 mt-2 flex-shrink-0">
                <x-button.primary wire:click="$set('open', true)">
                    Create new application
                </x-button.primary>
            </div>
        </div>
    </div>

    {{--List License Requests--}}
    <ul class="divide-y divide-gray-200">
        @forelse($licenseRequests as $licenseRequest)
            <li>
                <div class="px-4 py-4 sm:px-6 flex items-center space-x-4">

                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 object-cover rounded-full"
                             src="{{ $licenseRequest->channel_image }}"
                             alt="channel image">
                    </div>


                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ $licenseRequest->channel_name }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <x-license-request-status :status="$licenseRequest->status"/>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <a href="{{ $licenseRequest->channel }}">
                                    <p class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        {{ $licenseRequest->channel }}
                                    </p>
                                </a>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <!-- Heroicon name: solid/calendar -->
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     aria-hidden="true">
                                    <path fill-rule="evenodd"
                                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <p>
                                    Submitted on
                                    <time
                                        datetime="{{ $licenseRequest->created_at->format('Y-m-d') }}">{{ $licenseRequest->created_at->format('F j, Y') }}</time>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li colspan="5">
                <div class="flex justify-center items-center space-x-2">
                        <span class="font-medium py-8 text-cool-gray-400 text-xl">
                            No applications found...
                        </span>
                </div>
            </li>
        @endforelse
    </ul>

    {{--New Media Request Modal--}}
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
                <x-button.primary type="submit" wire:loading.disabled>Submit Application</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>

</section>

