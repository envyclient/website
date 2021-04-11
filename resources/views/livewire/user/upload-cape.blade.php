<x-card class="mt-4" title="Upload Cape" subtitle="Upload a custom cape for use">

    <img src="{{ $user->cape }}"
         alt="cape"
         class="rounded"
         width="256px"
         height="128px">

    <div class="mb-3">
        <x-filepond wire:model="cape"
                    accept="image/png"
                    maxFileSize="1024KB"
                    maxImageWidth="2048"
                    maxImageHeight="1024"
                    required
        />
        <div class="text-sm">
            The dimensions must be 2048x1024.
        </div>
    </div>

    @error('cape')
    <p class="mt-2 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror

    <x-slot name="footer">
        <x-small-notify/>
        <x-button.primary type="submit" wire:click="submit">
            Upload
        </x-button.primary>
        <x-button.danger wire:click="resetCape">
            Reset Cape
        </x-button.danger>
        <x-button.secondary>
            <a href="{{ asset('assets/capes/template.png') }}" download>
                Download Template
            </a>
        </x-button.secondary>
    </x-slot>

</x-card>
