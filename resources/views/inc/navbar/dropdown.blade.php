<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
    <a class="dropdown-item" href="{{ route('logout') }}"
       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
    </a>
    <form id="logout-form"
          action="{{ route('logout') }}"
          method="post"
          style="display: none;">
        @csrf
    </form>
</div>
