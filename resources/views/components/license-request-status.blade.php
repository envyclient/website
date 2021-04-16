@props(['status'])

@if($status === \App\Models\LicenseRequest::PENDING)
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
        pending
    </p>
@elseif($status === \App\Models\LicenseRequest::DENIED)
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
        denied
    </p>
@elseif($status === \App\Models\LicenseRequest::EXTENDED)
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
        extended
    </p>
@elseif($status === \App\Models\LicenseRequest::APPROVED)
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        approved
    </p>
@endif
