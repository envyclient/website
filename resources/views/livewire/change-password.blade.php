<div>
    <form wire:submit.prevent="submit">

        <div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input class="form-control"
                   type="text"
                   value="{{ $user->name }}"
                   id="name"
                   disabled>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input class="form-control"
                   type="text"
                   value="{{ $user->email }}"
                   id="email"
                   disabled>
        </div>

        <div class="form-group">
            <label for="current-password" class="form-label">Current Password</label>
            <input class="form-control @error('current_password') is-invalid @enderror"
                   type="password"
                   id="current-password"
                   wire:model="current_password"
                   required>

            @error('current_password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">New Password</label>
            <input class="form-control @error('password') is-invalid @enderror"
                   type="password"
                   id="password"
                   wire:model="password"
                   required>

            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirmation" class="form-label">Confirm Password</label>
            <input class="form-control @error('password_confirmation') is-invalid @enderror"
                   type="password"
                   id="password-confirmation"
                   wire:model="password_confirmation"
                   required>

            @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Change Password</button>
    </form>
</div>
