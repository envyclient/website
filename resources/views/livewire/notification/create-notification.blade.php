<div>
    <form wire:submit.prevent="submit">

        <div class="mb-3">
            <label class="form-label" for="version">Version</label>
            <input class="form-control @error('version') is-invalid @enderror"
                   type="number"
                   id="version"
                   wire:model.defer="version"
                   min="5.0"
                   step="0.1"
                   required>

            @error('version')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="notification">Notification</label>
            <textarea class="form-control @error('notification') is-invalid @enderror"
                      id="notification"
                      wire:model.defer="notification"
                      required>
            </textarea>

            @error('notification')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
</div>
