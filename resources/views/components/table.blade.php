<table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-cool-gray-200 shadow sm:rounded-md sm:overflow-hidden']) }}>
    <thead class="bg-gray-50">
    <tr>
        {{ $head }}
    </tr>
    </thead>

    <tbody class="bg-white divide-y divide-cool-gray-200">
    {{ $body }}
    </tbody>
</table>
