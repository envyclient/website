<div
    wire:ignore
    x-data="{ pond: null }"
    x-init="
        FilePond.setOptions({
            allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
                },
                revert: (filename, load) => {
                    @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                },
            },
        });
        pond = FilePond.create($refs.input);
    "
    @filepond-reset.window="pond.removeFiles()"
>
    <input type="file" x-ref="input" {{ $attributes->except(['wire:model']) }}>
</div>
