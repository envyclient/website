@props(['id'])

<div
    wire:ignore
    x-data="{
        easyMDE: null,
        value: @entangle($attributes->wire('model'))
    }"
    x-init="
        easyMDE = new EasyMDE({ element: document.getElementById('{{ $id }}') });
        easyMDE.codemirror.on('change', () => {
            value = easyMDE.value();
        });
    "
    id="easymde-{{ $id }}"
    @easymde-reset.window="easyMDE.value('')"
>
    <textarea
        id="{{ $id }}"
        class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm"
        {{ $attributes->except(['wire:model']) }}
    ></textarea>
</div>

