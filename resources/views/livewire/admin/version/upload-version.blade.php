<div>
    <form wire:submit.prevent="submit">

        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label" for="name">Name</label>
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

        <div class="mb-3">
            <label class="form-label" for="changelog">Changelog</label>
            <textarea class="form-control @error('name') is-invalid @enderror"
                      id="changelog"
                      wire:model.defer="changelog"
                      rows="3"
                      required></textarea>

            @error('changelog')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3 form-check form-switch">
            <label class="form-check-label" for="beta">
                Is Beta Version?
            </label>
            <input class="form-check-input"
                   type="checkbox"
                   id="beta"
                   wire:model.defer="beta">
        </div>

        <div class="mb-3">
            <label class="form-label">Files</label>
            <x-filepond wire:model="files"
                        multiple
                        required/>
        </div>

        <x-loading wire:loading wire:target="submit"/>
        <button type="submit"
                class="btn btn-success"
                {{ count($files) === 2 ? null : 'disabled' }}
                wire:loading.attr="disabled">
            Upload
        </button>
        <button type="button"
                class="btn btn-secondary"
                data-bs-toggle="modal"
                data-bs-target="#update-launcher"
                wire:loading.attr="disabled">
            Update Launcher
        </button>
    </form>
</div>
