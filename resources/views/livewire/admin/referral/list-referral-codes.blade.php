<div>
    <x-table>

        <x-slot name="head">
            <x-table.heading>User</x-table.heading>
            <x-table.heading>Code</x-table.heading>
            <x-table.heading>Uses</x-table.heading>
            <x-table.heading>Subscriptions</x-table.heading>
            <x-table.heading>Amount</x-table.heading>
            <x-table.heading>Last Usage</x-table.heading>
            <x-table.heading>Created</x-table.heading>
        </x-slot>

        <x-slot name="body">
            @forelse($codes as $code)
                <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $code->id }}">

                    <x-table.cell class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full"
                                 src="{{ $code->user->image }}"
                                 alt="user profile picture">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $code->user->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $code->user->email }}
                            </div>
                        </div>
                    </x-table.cell>

                    <x-table.cell>
                        {{ $code->code }}
                    </x-table.cell>

                    <x-table.cell>
                        @if($code->users->count() === 0)
                            0
                        @else
                            <x-button.link class="text-blue-600 hover:text-blue-900"
                                           wire:click="showUsersModal({{ $code->id }})">
                                {{ $code->users->count() }}
                            </x-button.link>
                        @endif
                    </x-table.cell>

                    <x-table.cell>
                        {{ $code->subscriptions->count() }}
                    </x-table.cell>

                    <x-table.cell>
                        @if($code->invoices()->sum('price') === 0)
                            $0
                        @else
                            <x-button.link class="text-blue-600 hover:text-blue-900"
                                           wire:click="showInvoicesModal({{ $code }})">
                                ${{ $code->invoices()->sum('price') }}
                            </x-button.link>
                        @endif
                    </x-table.cell>

                    <x-table.cell>
                        @if($code->users->last() === null)
                            &#10006;
                        @else
                            <strong>User</strong>: {{ $code->users->last()->name }}
                            <br>
                            <strong>Time</strong>: {{ $code->users->last()->referral_code_used_at->diffForHumans()  }}
                        @endif
                    </x-table.cell>

                    <x-table.cell>
                        {{ $code->created_at->diffForHumans() }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <span class="font-medium py-8 text-cool-gray-400 text-xl">
                               No referrals code...
                            </span>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-slot>

    </x-table>

    <x-modal.dialog wire:model.defer="showUsersModal">
        <x-slot name="title">Uses</x-slot>

        <x-slot name="content">
            <x-table>
                <x-slot name="head">
                    <x-table.heading>User</x-table.heading>
                    <x-table.heading>Code</x-table.heading>
                    <x-table.heading>Uses</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @foreach($showingUsers as $user)
                        <x-table.row wire:loading.class.delay="opacity-50" wire:key="showing-users-{{ $user->id }}">
                            <x-table.cell>{{ $user->name }}</x-table.cell>
                            <x-table.cell>{{ $user->subscription?->plan->name }}</x-table.cell>
                            <x-table.cell>{{ $user->subscription?->end_date->diffInDays() }}</x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>
        </x-slot>

        <x-slot name="footer">
            <x-button.secondary wire:click="$set('showUsersModal', false)">Close</x-button.secondary>
        </x-slot>
    </x-modal.dialog>

    <x-modal.dialog wire:model.defer="showInvoicesModal">
        <x-slot name="title">Invoices</x-slot>

        <x-slot name="content">
            <x-table>
                <x-slot name="head">
                    <x-table.heading>User</x-table.heading>
                    <x-table.heading>Method</x-table.heading>
                    <x-table.heading>Amount</x-table.heading>
                </x-slot>
                <x-slot name="body">
                    @foreach($showingInvoices as $invoice)
                        <x-table.row wire:loading.class.delay="opacity-50" wire:key="invoices-{{ $invoice->id }}">
                            <x-table.cell>{{ $invoice->user->name }}</x-table.cell>
                            <x-table.cell>{{ $invoice->method }}</x-table.cell>
                            <x-table.cell>${{ $invoice->price }} <span class="text-gray-400">USD</span></x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>
        </x-slot>

        <x-slot name="footer">
            <x-button.secondary wire:click="$set('showInvoicesModal', false)">Close</x-button.secondary>
        </x-slot>
    </x-modal.dialog>

</div>
