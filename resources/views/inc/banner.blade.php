<div
    class="bottom-0 inset-x-0 pb-2 sm:pb-5"
    x-data="{ show: true }"
    x-show="show"
    x-init="show = !docCookies.hasItem('banner')"
    :class="[show ? 'fixed' : 'hidden']"
>
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="p-2 rounded-lg bg-gray-600 shadow-lg sm:p-3">
            <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center">
                    <span class="flex p-2 rounded-lg bg-gray-800">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </span>
                    <p class="ml-3 font-medium text-white truncate">
                        <span class="md:inline">
                            Big news! We have a discord server.
                        </span>
                    </p>
                </div>
                <div class="order-3 mt-2 flex-shrink-0 w-full sm:order-2 sm:mt-0 sm:w-auto">
                    <a href="{{ config('services.discord.invite') }}"
                       class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-600 bg-white hover:bg-gray-50">
                        Join now
                    </a>
                </div>
                <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
                    <button type="button"
                            @click="show = false; docCookies.setItem('hide_banner', true, 60 * 60 * 24 * 3);"
                            class="-mr-1 flex p-2 rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-white">
                        <!-- Heroicon name: outline/x -->
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
