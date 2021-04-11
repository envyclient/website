<button
    x-data="{ clicked: @entangle($attributes->wire('model')), }"
    @click="clicked = !clicked"
    type="button"
    {{ $attributes->merge(['class' => 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500']) }}
    :class="[clicked ? 'bg-green-600' : 'bg-gray-200']"
>
    <span
        class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
        :class="[clicked ? 'translate-x-5' : 'translate-x-0']"
    ></span>
</button>
