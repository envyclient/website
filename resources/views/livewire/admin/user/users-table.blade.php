@section('title', 'Users')

<div class="flex flex-col">

    {{--Total Stats--}}
    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">

        {{--Total Users Stats--}}
        <x-stats-card title="Total Users">
            <x-slot name="icon">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </x-slot>
            {{ \App\Models\User::count() }}
        </x-stats-card>

        {{--Subscriptions Stats--}}
        <x-stats-card title="Subscriptions">
            <x-slot name="icon">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </x-slot>
            {{ \App\Models\Subscription::count() }}
        </x-stats-card>

        {{--Total Transactions Stats--}}
        <x-stats-card title="Total Transactions">
            <x-slot name="icon">
                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </x-slot>
            ${{ \App\Models\Invoice::sum('price') }} <span class="text-gray-500 ">USD</span>
        </x-stats-card>
    </dl>

    {{--Users Table--}}
    <div class="flex-col space-y-4 mt-4">

        {{--Table Filters--}}
        <div class="grid grid-cols-4 gap-6">

            {{--Search Filter--}}
            <div class="col-span-4 sm:col-span-1">
                <x-input.group for="search" label="Search">
                    <x-input.text wire:model="search" id="search"/>
                </x-input.group>
            </div>

            {{--Status Filter--}}
            <div class="col-span-4 sm:col-span-1">
                <x-input.group for="type" label="Status">
                    <x-input.select wire:model="type" id="type">
                        <option value="all" selected>All</option>
                        <option value="subscribed">Subscribed</option>
                        <option value="active-subscription">Active Subscription</option>
                        <option value="cancelled-subscription">Cancelled Subscription</option>
                        <option value="using-client">Using Client</option>
                        <option value="banned">Banned</option>
                    </x-input.select>
                </x-input.group>
            </div>

            {{--Plan Filter--}}
            <div class="col-span-4 sm:col-span-1">
                <x-input.group for="subscription" label="Plan">
                    <x-input.select wire:model="subscription" id="subscription">
                        <option value="ignore" selected>Ignore</option>
                        <option value="1">Free</option>
                        <option value="2">Standard</option>
                        <option value="3">Premium</option>
                    </x-input.select>
                </x-input.group>
            </div>

            {{--Referral Code Filter--}}
            <div class="col-span-4 sm:col-span-1">
                <x-input.group for="referralCode" label="Referral Code">
                    <x-input.select wire:model="referralCode" id="referralCode">
                        <option value="ignore" selected>Ignore</option>
                        @foreach(\App\Models\ReferralCode::all() as $code)
                            <option value="{{ $code->id }}">{{ $code->code }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
            </div>
        </div>

        {{--Users Table Data--}}
        <x-table>
            <x-slot name="head">
                <x-table.heading>Name</x-table.heading>
                <x-table.heading>Subscription</x-table.heading>
                <x-table.heading>Downloads</x-table.heading>
                <x-table.heading>Info</x-table.heading>
                <x-table.heading/>
            </x-slot>

            <x-slot name="body">
                @forelse ($users as $user)
                    <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $user->id }}">
                        <x-table.cell class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full"
                                     src="{{ $user->image }}"
                                     alt="user profile picture">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </x-table.cell>

                        <x-table.cell>
                            @if($user->subscription !== null)
                                <div class="text-sm text-gray-900">
                                    {{ $user->subscription->plan->name }} Plan
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ now()->diffInDays($user->subscription->end_date, false) }} days left
                                </div>
                            @else
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No Subscription
                                </span>
                            @endif
                        </x-table.cell>

                        <x-table.cell class="text-sm text-gray-500">
                            {{ count($user->downloads) }} downloads
                        </x-table.cell>

                        <x-table.cell class="space-y-1">
                            @if($user->current_account !== null)
                                <span
                                    class="px-2 block text-xs text-center leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Using Client
                                </span>
                            @endif
                            @if($user->hwid !== null)
                                <span
                                    class="px-2 block text-xs text-center leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    HWID Set
                                </span>
                            @endif
                            @if($user->banned)
                                <span
                                    class="px-2 block text-xs text-center leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Banned
                                </span>
                            @endif
                            @if($user->discord_id !== null)
                                <span
                                    class="px-2 block text-xs text-center leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Discord Connected
                                </span>
                            @endif
                            @if($user->referralCode != null)
                                <span
                                    class="px-2 block text-xs text-center leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    code: {{ $user->referralCode->code }}
                                </span>
                            @endif
                        </x-table.cell>

                        <x-table.cell>
                            <x-button.link class="block text-indigo-600 hover:text-indigo-900"
                                           @click="$dispatch('edit-user-modal', {{ $user->id }})">
                                Edit
                            </x-button.link>
                            @if($user->subscription?->plan->id === 1)
                                <x-button.link class="block text-red-600 hover:text-red-900"
                                               onclick="confirm('Remove Free Subscription?') || event.stopImmediatePropagation()"
                                               wire:click.prevent="updateFreeSubscription({{ $user->id }}, true)">
                                    Remove Subscription
                                </x-button.link>
                            @elseif($user->subscription === null)
                                <x-button.link class="block text-indigo-600 hover:text-indigo-900"
                                               onclick="confirm('Give Free Subscription?') || event.stopImmediatePropagation()"
                                               wire:click.prevent="updateFreeSubscription({{ $user->id }}, false)">
                                    Give Subscription
                                </x-button.link>
                            @endif
                            @if($user->banned)
                                <x-button.link class="block text-indigo-600 hover:text-indigo-900"
                                               onclick="confirm('Ban user?') || event.stopImmediatePropagation()"
                                               wire:click.prevent="updateUserBan({{ $user->id }}, false)">
                                    Unban User
                                </x-button.link>
                            @else
                                <x-button.link class="block text-red-600 hover:text-red-900"
                                               onclick="confirm('Unban user?') || event.stopImmediatePropagation()"
                                               wire:click.prevent="updateUserBan({{ $user->id }}, true)">
                                    Ban User
                                </x-button.link>
                            @endif
                            @if($user->hwid !== null)
                                <x-button.link class="block text-red-600 hover:text-red-900"
                                               onclick="confirm('Reset user HWID?') || event.stopImmediatePropagation()"
                                               wire:click.prevent="resetUserHWID({{ $user->id }})">
                                    Reset HWID
                                </x-button.link>
                            @endif
                            <form action="{{ route('users.delete') }}" method="post">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="from_admin">
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <x-button.link class="block text-red-600 hover:text-red-900" type="submit">
                                    Delete User
                                </x-button.link>
                            </form>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.cell colspan="5">
                            <div class="flex justify-center items-center space-x-2">
                                <span class="font-medium py-8 text-cool-gray-400 text-xl">
                                    No Users Found...
                                </span>
                            </div>
                        </x-table.cell>
                    </x-table.row>
                @endforelse
            </x-slot>
        </x-table>

        {{--Users Table Pagination Links--}}
        {{ $users->links() }}
    </div>

    {{--Edit User Modal--}}
    @livewire('admin.user.edit-user-modal')
</div>
