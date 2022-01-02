<div wire:poll>
    <div class="text-center">
        <h1 class="text-4xl font-bold tracking-wider">
            WeChat Pay
        </h1>
        <h5 class="text-lg text-gray-500 mt-1">
            {{ $source['plan']['name'] }} Plan - (${{ $source['plan']['price'] }} USD,
            ${{ number_format($source['plan']['cad_price'] / 100) }} CAD)
        </h5>
    </div>
    <div class="p-5 flex flex-col md:flex-row">
        <div class="w-2/5 flex flex-col mr-5">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="flex mb-4">
                    <span class="h-8 inline-flex rounded-md shadow-sm">
                          <a href="{{ route('home') }}"
                             class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-gray-300 focus:shadow-outline-gray active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                            Home
                          </a>
                    </span>
                </div>
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <x-table>
                        <x-slot name="head">
                            <x-table.heading>#</x-table.heading>
                            <x-table.heading>type</x-table.heading>
                            <x-table.heading>message</x-table.heading>
                            <x-table.heading>time</x-table.heading>
                        </x-slot>

                        <x-slot name="body">
                            @foreach($source['events'] as $event)
                                <x-table.row wire:key="row-{{ $loop->iteration}}">

                                    <x-table.cell class="flex items-center">
                                        {{ $loop->iteration }}
                                    </x-table.cell>

                                    <x-table.cell>
                                        @if($event['type'] === \App\Enums\StripeSourceStatus::PENDING)
                                            <span
                                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-blue-100 text-blue-800">
                                                pending
                                            </span>
                                        @elseif($event['type'] === \App\Enums\StripeSourceStatus::CANCELED)
                                            <span
                                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-yellow-100 text-yellow-800">
                                                expired
                                            </span>
                                        @elseif($event['type'] === \App\Enums\StripeSourceStatus::FAILED)
                                            <span
                                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-red-100 text-red-800">
                                                failed
                                            </span>
                                        @elseif($event['type'] === \App\Enums\StripeSourceStatus::CHARGEABLE)
                                            <span
                                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-gray-100 text-gray-800">
                                                authorized
                                            </span>
                                            <span class="badge rounded-pill bg-info text-dark"></span>
                                        @elseif($event['type'] === \App\Enums\StripeSourceStatus::SUCCEEDED)
                                            <span
                                                class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-green-100 text-green-800">
                                                succeeded
                                            </span>
                                        @endif
                                    </x-table.cell>

                                    <x-table.cell class="text-gray-500">
                                        {{ $event['message'] }}
                                    </x-table.cell>

                                    <x-table.cell class="text-gray-500">
                                        {{ \Illuminate\Support\Carbon::parse($event['created_at'])->diffForHumans() }}
                                    </x-table.cell>
                                </x-table.row>
                            @endforeach
                        </x-slot>
                    </x-table>
                </div>
            </div>
        </div>
        <div class="w-3/5 mt-5 md:mt-0 md:ml-5">
            <div class="flex-col bg-white shadow overflow-hidden sm:rounded-lg justify-center items-center flex py-4">
                <h1 class="text-lg">QR Code</h1>
                <img src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl={{ urlencode($source['url']) }}"
                     alt="qr code"/>
            </div>
        </div>
    </div>
    @if($source['status'] !== \App\Enums\StripeSourceStatus::PENDING && $source['status'] !== \App\Enums\StripeSourceStatus::CHARGEABLE)
        <a href="{{ route('home') }}"
           class="block w-full py-2 px-6 cursor-pointer text-center text-xl text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out">
            Dashboard
        </a>
    @endif
</div>
