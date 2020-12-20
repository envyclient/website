@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fas fa-lock" style="padding-right:10px;"></i> Security
        </div>
        <div class="card">
            <div class="card-body">
                @livewire('change-password')
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-outline-danger btn-lg btn-block" data-toggle="modal"
                    data-target="#disableAccountModal">
                Disable Account
            </button>
        </div>
        <br>
    </div>

    <!-- disable account modal -->
    <div class="modal fade" id="disableAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Disable Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    By disabling your account you will lose access to your account. Are you sure you want to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
