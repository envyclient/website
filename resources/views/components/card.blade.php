@props(['title', 'subtitle'])

<div {{ $attributes->merge(['class' => 'shadow sm:rounded-md sm:overflow-hidden']) }}>
    <div class="bg-white py-6 px-4 sm:p-6">
        <div>
            @if(isset($title))
                <h2 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $title }}
                </h2>
            @endif
            @if(isset($subtitle))
                <p class="mt-1 text-sm text-gray-500">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>

    @if(isset($footer))
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
