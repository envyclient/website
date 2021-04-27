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
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType,
            FilePondPluginImageValidateSize,
        );
        pond = FilePond.create($refs.input);
        pond.setOptions({
            credits: false,
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
    "
    @filepond-reset.window="pond.removeFiles()"
>
    <input type="file" x-ref="input" {{ $attributes->except(['wire:model']) }}>
</div>
