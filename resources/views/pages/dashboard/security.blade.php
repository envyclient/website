@extends('layouts.dash')

@section('title', 'Security')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-dark" style="font-size:25px;">
            <svg class="mb-1" style="width:32px;height:32px;" viewBox="0 0 24 24">
                <path fill="currentColor"
                      d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.1 14.8,9.5V11C15.4,11 16,11.6 16,12.3V15.8C16,16.4 15.4,17 14.7,17H9.2C8.6,17 8,16.4 8,15.7V12.2C8,11.6 8.6,11 9.2,11V9.5C9.2,8.1 10.6,7 12,7M12,8.2C11.2,8.2 10.5,8.7 10.5,9.5V11H13.5V9.5C13.5,8.7 12.8,8.2 12,8.2Z"/>
            </svg>
            Security
        </div>
        <div class="card">
            <div class="card-body">
                @livewire('change-password')
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
