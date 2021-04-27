<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'py-2 px-4 border rounded-md text-sm leading-5 font-medium focus:outline-none focus:border-gray-300 focus:shadow-outline-gray transition duration-150 ease-in-out disabled:opacity-75 disabled:cursor-not-allowed',
    ]) }}
>
    {{ $slot }}
</button>
