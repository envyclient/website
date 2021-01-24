@extends('layouts.dash')

@section('title', 'Security')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-dark" style="font-size:25px;">
            <i class="fas fa-lock" style="padding-right:10px;"></i> Security
        </div>
        <div class="card">
            <div class="card-body">
                @livewire('change-password')
            </div>
        </div>
        <div class="mt-3 mb-3 d-grid gap-2">
            <button type="button"
                    class="btn btn-outline-danger btn-lg"
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
