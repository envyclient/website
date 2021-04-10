@props([
'label',
'for',
])

<div>
    @if(isset($label))
        <label for="{{ $for }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    {{ $slot }}

    @error($for)
    <span class="text-sm text-red-600">
        {{ $message }}
    </span>
    @enderror
</div>
