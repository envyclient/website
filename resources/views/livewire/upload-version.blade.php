<div>
    <form wire:submit.prevent="save">

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control @error('name') is-invalid @enderror"
                   placeholder="Name"
                   type="text"
                   id="name"
                   wire:model.lazy="name"
                   required>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="beta" wire:model.lazy="beta">
                <label class="custom-control-label" for="beta">Is Beta Version?</label>
            </div>
        </div>

        <div class="form-group">
            <div class="custom-file">
                <input type="file"
                       class="custom-file-input @error('file') is-invalid @enderror"
                       id="file"
                       wire:model="file"
                       required>
                <label class="custom-file-label" for="file">Choose file</label>

                @error('file')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <span wire:loading wire:target="file" class="valid-feedback" role="alert">
                <strong>Uploading...</strong>
            </span>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
