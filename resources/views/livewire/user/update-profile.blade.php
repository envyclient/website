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
                <label for="name" class="form-label">Name</label>
                <input class="form-control @error('name') is-invalid @enderror"
                       type="text"
                       id="name"
                       wire:model.defer="name"
                       required>

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input class="form-control @error('email') is-invalid @enderror"
                       type="email"
                       id="email"
                       wire:model.defer="email"
                       required>

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-success">Update Profile</button>
        </div>
    </div>
</form>--}}
<form wire:submit.prevent="submit">
    <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="bg-white py-6 px-4 sm:p-6">
            <div>
                <h2 id="payment_details_heading" class="text-lg leading-6 font-medium text-gray-900">
                    Account details
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Update your account information. Please note that if you change your email you will need
                    to verify it.
                </p>
            </div>

            <div class="mt-6 grid grid-cols-4 gap-6">

                <div class="col-span-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text"
                               id="name"
                               wire:model.defer="name"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm @error('name') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror">

                        @error('name')
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

                    @error('name')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="col-span-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <input type="text"
                               id="email"
                               wire:model.defer="email"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm @error('email') border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 @enderror">

                        @error('email')
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

                    @error('email')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <x-small-notify class="mr-3"/>
            <x-button.primary type="submit">
                Save
            </x-button.primary>
        </div>
    </div>
</form>

