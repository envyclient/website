<button {{ $attributes->merge(['class' => 'flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out disabled:opacity-75 disabled:cursor-not-allowed', 'type' => 'button']) }}>
    <x-loading wire:loading wire:target="{{ $attributes->wire('target')->value }}" class="la-sm mr-2 mt-0.5"/>
    {{ $slot }}
</button>
