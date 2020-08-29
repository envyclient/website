<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
    @if(auth()->user()->admin)
        <a class="dropdown-item" href="{{ route('home') }}">
            Dashboard
        </a>
        <a class="dropdown-item" href="{{ route('admin') }}">
            Admin
        </a>
    @endif
    <a class="dropdown-item" href="{{ route('logout') }}"
       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        {{ __('Logout') }}
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST"
          style="display: none;">
        @csrf
    </form>
</div>
