@props(['status'])

@if($status === 'pending')
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
        pending
    </p>
@elseif($status === 'denied')
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
        denied
    </p>
@elseif($status === 'extended')
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
        extended
    </p>
@elseif($status === 'approved')
    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
        approved
    </p>
@endif
