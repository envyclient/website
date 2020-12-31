<div>
    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <label class="form-label" for="user">User</label>
            <input class="form-control @error('user') is-invalid @enderror"
                   type="text"
                   id="user"
                   wire:model="user"
                   required>

            @error('user')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="code">Code</label>
            <input class="form-control @error('code') is-invalid @enderror"
                   type="text"
                   id="code"
                   wire:model="code"
                   required>

            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
