@props([
'maxFileSize', 'maxTotalFileSize',
'minImageWidth', 'maxImageWidth',
'minImageHeight', 'maxImageHeight',
'fileTypes'
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
            required: {{ isset($attributes['required'])  ? 'true' : 'false' }},
            maxFileSize: '{{ $maxFileSize ?? null }}',
            maxTotalFileSize: '{{ $maxTotalFileSize ?? null }}',
            imageValidateSizeMinWidth: '{{ $minImageWidth ?? 1 }}',
            imageValidateSizeMaxWidth: '{{ $maxImageWidth ?? 65535 }}',
            imageValidateSizeMinHeight: '{{ $minImageHeight ?? 1 }}',
            imageValidateSizeMaxHeight: '{{ $maxImageHeight ?? 65535 }}',
            allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
            allowFileTypeValidation: {{ isset($attributes['fileTypes']) ? 'true' : 'false' }},
            acceptedFileTypes: {{ isset($attributes['fileTypes']) ? $fileTypes : '[]' }},
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
