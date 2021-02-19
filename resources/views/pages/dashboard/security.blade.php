@extends('layouts.dash')

@section('title', 'Profile')

@section('content')

    <div class="row">
        <div class="col-sm">
            <div class="alert alert-dark" style="font-size:25px;">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                </svg>
                Profile
            </div>

            @livewire('user.update-profile')
        </div>

        <div class="col-sm">
            <div class="alert alert-dark" style="font-size:25px;">
                <svg class="pb-1" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                          d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
                </svg>
                Password
            </div>

            @livewire('user.update-password')
        </div>
    </div>

    <div class="mt-3 mb-3 d-grid gap-2">
        <button type="button"
                class="btn btn-danger btn-lg"
                data-bs-toggle="modal"
                data-bs-target="#disableAccountModal">
            Disable Account
        </button>
    </div>

    <!-- disable account modal -->
    <div class="modal fade" id="disableAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Disable Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    By disabling your account you will lose access to your account. Are you sure you want to proceed?
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        NO, CANCEL
                    </button>
                    <form action="{{ route('users.disable') }}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <input class="btn btn-danger m-sm-2" type="submit" value="YES">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
