@section('title', 'Verify Email')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Verify your email address
        </h2>

        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                sign out
            </a>

        <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
            @csrf
        </form>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            @if (session('resent'))
                <div class="rounded-md bg-green-100 p-4 mb-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="ml-3">
                            <p class="text-sm leading-5 font-medium text-green-800">
                                A fresh verification link has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-sm text-gray-700">
                <p>Before proceeding, please check your email for a verification link.</p>
                <p class="mt-3">
                    If you did not receive the email, <a wire:click="submit" class="text-green-700 cursor-pointer hover:text-green-600 focus:outline-none focus:underline transition ease-in-out duration-150">click here to request another</a>.
                </p>
            </div>
        </div>
    </div>
</div>
