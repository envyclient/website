@section('title', 'License Requests')

<div>

    @include('inc.notifications')

    <div class="col-span-4 sm:col-span-1">
        <x-input.group for="status" label="Status">
            <x-input.select wire:model="status" id="status">
                <option value="all" selected>All</option>
                <option value="{{ \App\Enums\LicenseRequest::PENDING->value}}">Pending</option>
                <option value="{{ \App\Enums\LicenseRequest::APPROVED->value }}">Approved</option>
                <option value="{{ \App\Enums\LicenseRequest::DENIED->value }}">Denied</option>
            </x-input.select>
        </x-input.group>
    </div>

    <x-table class="mt-4">

        <x-slot name="head">
            <x-table.heading>Account</x-table.heading>
            <x-table.heading>Status</x-table.heading>
            <x-table.heading>Created</x-table.heading>
            <x-table.heading/>
        </x-slot>

        <x-slot name="body">
            @forelse($requests as $request)
                <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $request->id }}">
                    <x-table.cell class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full"
                                 src="{{ $request->channel_image }}"
                                 alt="channel mage">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ $request->channel }}">
                                    {{ $request->channel_name }}
                                </a>
                            </div>

                            <div class="text-sm text-gray-500 group flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $request->user->name }}
                            </div>
                        </div>
                    </x-table.cell>

                    <x-table.cell>
                        <x-license-request-status :status="$request->status"/>
                    </x-table.cell>

                    <x-table.cell class="text-sm text-gray-500">
                        {{ $request->created_at->diffForHumans() }}
                    </x-table.cell>

                    <x-table.cell>
                        @if($request->status === \App\Enums\LicenseRequest::PENDING->value)
                            <x-button.link class="block text-indigo-600 hover:text-indigo-900"
                                           wire:click="approve({{ $request->id }})">
                                Approve
                            </x-button.link>
                            <x-button.link class="block text-red-600 hover:text-red-900"
                                           onclick="window.livewire.emit('DENY_REQUEST', {id : {{ $request->id }}, message: prompt('Reason')});">
                                Deny
                            </x-button.link>
                        @endif
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="3">
                        <div class="flex justify-center items-center space-x-2">
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">
                                No License Requests...
                            </span>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-slot>

    </x-table>

</div>
