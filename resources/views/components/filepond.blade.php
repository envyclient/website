@props([
    'maxFileSize', 'maxTotalFileSize',
    'minImageWidth', 'maxImageWidth',
    'minImageHeight', 'maxImageHeight'
])

<div
    wire:ignore
    x-data="{ pond: null }"
    x-init="
        FilePond.registerPlugin(
            FilePondPluginImageValidateSize,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        FilePond.setOptions({
            required: true,
            maxFileSize: '{{ isset($maxFileSize) ? $maxFileSize : null }}',
            maxTotalFileSize: '{{ isset($maxTotalFileSize) ? $maxTotalFileSize : null }}',
            imageValidateSizeMinWidth: '{{ isset($minImageWidth) ? $minImageWidth : 1 }}',
            imageValidateSizeMaxWidth: '{{ isset($maxImageWidth) ? $maxImageWidth : 65535 }}',
            imageValidateSizeMinHeight: '{{ isset($minImageHeight) ? $minImageHeight : 1 }}',
            imageValidateSizeMaxHeight: '{{ isset($maxImageHeight) ? $maxImageHeight : 65535 }}',
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
