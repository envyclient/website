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
</form>


