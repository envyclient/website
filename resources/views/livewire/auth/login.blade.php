@section('title', 'Login')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <a href="{{ route('index') }}">
            <x-logo class="w-auto h-16 mx-auto"/>
        </a>
        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ route('register') }}"
               class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                create a new account
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            <form wire:submit.prevent="login">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 leading-5">
                        Email address
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.lazy="email"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-gray focus:border-gray-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror"
                               id="email"
                               type="email"
                               autocomplete="email"
                               required
                               autofocus>
                    </div>

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 leading-5">
                        Password
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.lazy="password"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-gray focus:border-gray-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror"
                               id="password"
                               type="password"
                               autocomplete="current-password"
                               required>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center">
                        <input wire:model.lazy="remember"
                               class="form-checkbox w-4 h-4 text-green-600 transition duration-150 ease-in-out"
                               id="remember"
                               type="checkbox">
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            Remember
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}"
                           class="font-medium text-green-600 hover:text-green-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit"
                                class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-500 focus:outline-none focus:border-green-300 focus:shadow-outline-green active:bg-green-700 transition duration-150 ease-in-out">
                            Sign in
                        </button>
                    </span>
                </div>

                <div class="flex justify-between items-center mt-2">
                    <hr class="w-full">
                    <span class="p-3 text-gray-400 mb-1">or</span>
                    <hr class="w-full">
                </div>

                <div class="container">
                    <a href="{{ route('login.discord') }}"
                       class="transition transition-colors duration-200 block border border-gray-300 border-2 rounded-md p-1 w-32 m-auto text-gray-500 hover:text-white hover:bg-gray-500">
                        <svg style="width:24px;height:24px;" viewBox="0 0 24 24" class="m-auto">
                            <path fill="currentColor"
                                  d="M22,24L16.75,19L17.38,21H4.5A2.5,2.5 0 0,1 2,18.5V3.5A2.5,2.5 0 0,1 4.5,1H19.5A2.5,2.5 0 0,1 22,3.5V24M12,6.8C9.32,6.8 7.44,7.95 7.44,7.95C8.47,7.03 10.27,6.5 10.27,6.5L10.1,6.33C8.41,6.36 6.88,7.53 6.88,7.53C5.16,11.12 5.27,14.22 5.27,14.22C6.67,16.03 8.75,15.9 8.75,15.9L9.46,15C8.21,14.73 7.42,13.62 7.42,13.62C7.42,13.62 9.3,14.9 12,14.9C14.7,14.9 16.58,13.62 16.58,13.62C16.58,13.62 15.79,14.73 14.54,15L15.25,15.9C15.25,15.9 17.33,16.03 18.73,14.22C18.73,14.22 18.84,11.12 17.12,7.53C17.12,7.53 15.59,6.36 13.9,6.33L13.73,6.5C13.73,6.5 15.53,7.03 16.56,7.95C16.56,7.95 14.68,6.8 12,6.8M9.93,10.59C10.58,10.59 11.11,11.16 11.1,11.86C11.1,12.55 10.58,13.13 9.93,13.13C9.29,13.13 8.77,12.55 8.77,11.86C8.77,11.16 9.28,10.59 9.93,10.59M14.1,10.59C14.75,10.59 15.27,11.16 15.27,11.86C15.27,12.55 14.75,13.13 14.1,13.13C13.46,13.13 12.94,12.55 12.94,11.86C12.94,11.16 13.45,10.59 14.1,10.59Z"/>
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
