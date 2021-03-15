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

    <!-- Update Launcher Modal -->
    <div class="modal fade" id="update-launcher" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Launcher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('launcher.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="launcher-version" class="form-label">Version</label>
                            <input type="text"
                                   class="form-control"
                                   id="launcher-version"
                                   name="launcher-version"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="launcher" class="form-label">Launcher</label>
                            <input class="form-control @error('launcher') is-invalid @enderror"
                                   type="file"
                                   id="launcher"
                                   name="launcher"
                                   accept=".exe"
                                   required>

                            @error('launcher')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
