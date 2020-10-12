@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <div class="alert alert-secondary" style="font-size:25px;">
            <i class="fas fa-lock" style="padding-right:10px;"></i> Security
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('user.update-password') }}" accept-charset="UTF-8">
                    @csrf
                    <input name="_method" type="hidden" value="PUT">

                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" disabled name="name" type="text" value="{{ $user->name }}"
                               id="name">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" disabled name="email" type="text" value="{{ $user->email }}"
                               id="email">
                    </div>
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input class="form-control" required name="current_password" type="password"
                               id="current_password">
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input class="form-control" required name="password" type="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input class="form-control" required name="password_confirmation" type="password"
                               id="password_confirmation">
                    </div>
                    <input class="btn btn-success" type="submit" value="Change Password">
                </form>
            </div>
        </div>
        <div class="mt-3">
            @if($user->hasSubscription())
                <button type="button" class="btn btn-outline-danger btn-lg btn-block" disabled>
                    You cannot disable your account because you have an active subscription.
                </button>
            @else
                <button type="button" class="btn btn-outline-danger btn-lg btn-block" data-toggle="modal"
                        data-target="#disableAccountModal">
                    Disable Account
                </button>
            @endif
        </div>
    </div>

    <!-- disable account modal -->
    <div class="modal fade" id="disableAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Wooooow! Are you sure there bud?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body" style="color:red;">
                    By disabling your account you will lose access to your account.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <form method="POST" action="{{ route('user.disable') }}" accept-charset="UTF-8">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                        <input class="btn btn-outline-danger m-sm-2" type="submit" value="Disable Account">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
