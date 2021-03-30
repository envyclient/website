<form wire:submit.prevent="submit">
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
</form>

