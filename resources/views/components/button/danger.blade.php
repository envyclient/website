<x-button {{ $attributes->merge(['class' => 'text-white bg-red-600 hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-900']) }}>
    {{ $slot }}
</x-button>
