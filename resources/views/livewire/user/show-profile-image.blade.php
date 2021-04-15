<div>
    <div class="flex-shrink-0 w-full group block">
        <div class="flex items-center">
            <div>
                <img class="inline-block h-9 w-9 rounded-full"
                     src="{{ auth()->user()->image }}"
                     alt="user image">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">
                    {{ auth()->user()->name }}
                </p>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">
                        {{ __('Logout') }}
                    </p>
                </a>
            </div>
        </div>
    </div>
    <form id="logout-form"
          action="{{ route('logout') }}"
          method="post"
          style="display: none;">
        @csrf
    </form>
</div>
