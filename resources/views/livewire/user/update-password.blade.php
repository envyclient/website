{{--<form wire:submit.prevent="submit">
    <div class="card shadow-sm">

        <div class="card-body">

            @if (session()->has('message'))
                <div class="alert alert-success show" role="alert">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="mb-1 me-1">
                        <path
                            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            <div class="mb-3">
                <label for="current-password" class="form-label">Current Password</label>
                <input class="form-control @error('current_password') is-invalid @enderror"
                       type="password"
                       id="current-password"
                       wire:model.defer="current_password"
                       required>

                <div class="form-text">
                    If you registered using Discord please check your email.
                </div>

                @error('current_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input class="form-control @error('password') is-invalid @enderror"
                       type="password"
                       id="password"
                       wire:model.defer="password"
                       required>

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div>
                <label for="password-confirmation" class="form-label">Confirm Password</label>
                <input class="form-control @error('password_confirmation') is-invalid @enderror"
                       type="password"
                       id="password-confirmation"
                       wire:model.defer="password_confirmation"
                       required>

                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Update Password</button>
            <a type="button" class="btn btn-outline-danger" href="{{ route('password.request') }}">Forget Password</a>
        </div>

    </div>
</form>--}}

<form wire:submit.prevent="submit">
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 sm:p-6">
            <div>
                <h2 id="payment_details_heading" class="text-lg leading-6 font-medium text-gray-900">
                    Account security
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Update your account password. Please do not reuse password.
                </p>
            </div>

            <div class="mt-6 grid grid-cols-4 gap-6">

                <div class="col-span-4">
                    <label for="current-password" class="block text-sm font-medium text-gray-700">
                        Current Password
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="password"
                               id="current-password"
                               wire:model.defer="current_password"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm @error('current_password') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror">

                        @error('current_password')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @enderror
                    </div>

                    @error('current_password')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="password"
                               id="password"
                               wire:model.defer="password"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm @error('password') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror">

                        @error('password')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @enderror
                    </div>

                    @error('password')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label for="password-confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="password"
                               id="password-confirmation"
                               wire:model.defer="password_confirmation"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm @error('password_confirmation') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror">

                        @error('password_confirmation')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        @enderror
                    </div>

                    @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

            </div>
        </div>
        {{--TODO: show notification--}}
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit"
                    class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                Update
            </button>
        </div>
    </div>
</form>


