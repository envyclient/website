<div>
    <a href="{{ route('logout') }}"
       class="flex-shrink-0 w-full group block"
       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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
                <p class="text-xs font-medium text-gray-300 group-hover:text-gray-200">
                    {{ __('Logout') }}
                </p>
            </div>
        </div>
    </a>
    <form id="logout-form"
          action="{{ route('logout') }}"
          method="post"
          style="display: none;">
        @csrf
    </form>
</div>
