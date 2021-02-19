<div wire:poll>
    <div class="text-center">
        <h1 class="text-4xl font-bold tracking-wider">
            WeChat Pay
        </h1>
        <h5 class="text-lg text-gray-500 mt-1">
            {{ $source->plan->name }} Plan - (${{ $source->plan->price }} USD, ${{ number_format($source->plan->cad_price / 100) }} CAD)
        </h5>
    </div>
    <div class="p-5 flex flex-col md:flex-row">
        <div class="w-2/5 flex flex-col mr-5">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="flex mb-4">
                        <span class="h-8 inline-flex rounded-md shadow-sm">
                              <a href="{{ route('home') }}"
                                 class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                Home
                              </a>
                        </span>
                </div>
                <div
                    class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                type
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                message
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                time
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white">
                        @foreach($events as $event)
                            <tr>
                                <th scope="row"
                                    class="cursor-pointer px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $loop->iteration }}
                                </th>
                                <td class="cursor-pointer px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    @if($event->type === 'pending')
                                        <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-blue-100 text-blue-800">
                                     pending
                                    </span>
                                    @elseif($event->type === 'canceled')
                                        <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-yellow-100 text-yellow-800">
                                      expired
                                    </span>
                                    @elseif($event->type === 'failed')
                                        <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-red-100 text-red-800">
                                     failed
                                    </span>
                                    @elseif($event->type === 'chargeable')
                                        <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-gray-100 text-gray-800">
                                     authorized
                                    </span>
                                        <span class="badge rounded-pill bg-info text-dark"></span>
                                    @elseif($event->type === 'succeeded')
                                        <span
                                            class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium leading-5 bg-green-100 text-green-800">
                                     succeeded
                                    </span>
                                    @endif
                                </td>
                                <td class="cursor-pointer px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $event->message }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                    {{ $event->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="w-3/5 mt-5 md:mt-0 md:ml-5">
            <div class="flex-col bg-white shadow overflow-hidden sm:rounded-lg justify-center items-center flex py-4">
                <h1 class="text-lg">QR Code</h1>
                <img src="https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl={{ urlencode($source->url) }}"/>
            </div>
        </div>
    </div>
    @if($source->status !== 'pending' && $source->status !== 'chargeable')
        <a href="{{ route('home') }}"
           class="block w-full py-2 px-6 cursor-pointer text-center text-xl text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out">
            Dashboard
        </a>
    @endif
</div>
