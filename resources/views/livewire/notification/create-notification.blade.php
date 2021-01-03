<div>
    <form wire:submit.prevent="submit">

        <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select" id="type" wire:model.defer="type">
                <option value="info" selected>Info</option>
                <option value="warning">Warning</option>
            </select>

            @error('type')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="notification">Notification Message</label>
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
