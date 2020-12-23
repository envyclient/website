<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>
                <div class="card-body">
                    <form wire:submit.prevent="register">

                        <div class="mb-3 row">
                            <label for="name" class="col-sm-2 col-form-label">
                                {{ __('Name') }}
                            </label>

                            <div class="col-sm-10">
                                <input id="name"
                                       type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       wire:model.defer="name"
                                       required
                                       autocomplete="name"
                                       autofocus>
                                <p class="mb-0 small text-muted">
                                    This cannot be changed.
                                </p>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">
                                {{ __('E-Mail Address') }}
                            </label>

                            <div class="col-sm-10">
                                <input id="email"
                                       type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       wire:model.defer="email"
                                       required
                                       autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-sm-2 col-form-label">
                                {{ __('Password') }}
                            </label>

                            <div class="col-sm-10">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       wire:model.defer="password"
                                       required>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password-confirm" class="col-sm-2 col-form-label">
                                {{ __('Confirm Password') }}
                            </label>

                            <div class="col-sm-10">
                                <input id="password-confirm"
                                       type="password"
                                       class="form-control"
                                       wire:model.defer="passwordConfirmation"
                                       required>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-sm-10 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
