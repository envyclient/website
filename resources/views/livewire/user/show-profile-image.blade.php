<div class="row">
    <div class="col-3">
        <img src="{{ $user->image }}"
             class="rounded-circle"
             alt="user image"
             width="32px"
             height="32px">
    </div>
    <div class="col-8">
        <h6 class="text-white m-0">
            {{  $user->name }}
        </h6>
        <a href="{{ route('logout') }}"
           class="text-white text-muted m-0"
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
</div>
