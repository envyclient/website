@props([
    'label',
    'for',
])

<div>
    <label for="{{ $for }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
    </label>

    {{ $slot }}

    @error($for)
    <p class="mt-1 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror
</div>
