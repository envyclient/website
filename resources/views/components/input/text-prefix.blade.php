@props(['id', 'errors'])

@if($errors->has($id))
    <div class="relative mt-1 flex rounded-md shadow-sm">
        <span
            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
            {{ $slot }}
        </span>
        <input
            {{ $attributes->merge(['class' => 'block w-full border border-gray-300 rounded-none rounded-r-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500', 'type' => 'text']) }}
        />
    </div>

    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                  clip-rule="evenodd"/>
        </svg>
    </div>
@else
    <div class="mt-1 flex rounded-md shadow-sm">
        <span
            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
            {{ $slot }}
        </span>
        <input
            {{ $attributes->merge(['class' => 'flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300', 'type' => 'text']) }}
        />
    </div>
@endif

