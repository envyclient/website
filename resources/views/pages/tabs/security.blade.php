<!--- Security Section --->
<div class="tab-pane fade" id="security" role="tabpanel">
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
                    <input class="form-control" readonly name="name" type="text" value="{{ $user->name }}"
                           id="name">
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-control" readonly name="email" type="text" value="{{ $user->email }}"
                           id="email">
                </div>
                <div class="form-group">
                    <label for="password_current" class="form-label">Current Password</label>
                    <input class="form-control" required name="password_current" type="password"
                           id="password_current">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">New password</label>
                    <input class="form-control" required name="password" type="password" id="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">New Password Confirm</label>
                    <input class="form-control" required name="password_confirmation" type="password"
                           id="password_confirmation">
                </div>
                <input class="btn btn-success" type="submit" value="Change Password">
            </form>
        </div>
    </div>
    <div class="card mt-3" style="width: 100%;">
        <ul class="list-group list-group-flush">
            <button type="button" class="btn btn-outline-danger btn-lg btn-block" data-toggle="modal"
                    data-target="#disableAccountModal">
                Disable Account
            </button>
        </ul>
    </div>
</div>
