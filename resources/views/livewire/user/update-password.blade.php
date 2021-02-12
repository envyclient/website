<form wire:submit.prevent="submit">
    <div class="card shadow-sm">

        <div class="card-body">

            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
</form>

