<div x-data="{ open: false }"
     x-show="open"
     x-on:keydown.escape.window="open = false"
     @edit-user-modal-open.window="open = true; $wire.call('edit', $event.detail)"
     @edit-user-modal-close.window="open = false"
     x-cloak
>
    <form wire:submit.prevent="save">
        <div class="modal" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Name</label>
                            <input id="name"
                                   class="form-control @error('user.name') is-invalid @enderror"
                                   wire:model.defer="user.name">

                            @error('user.name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <label class="form-label" for="hwid">HWID</label>
                            <input class="form-check-input @error('user.hwid') is-invalid @enderror"
                                   type="checkbox"
                                   id="hwid"
                                   wire:model.defer="user.hwid">

                            @error('user.hwid')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <label class="form-label" for="banned">Banned</label>
                            <input class="form-check-input @error('user.banned') is-invalid @enderror"
                                   type="checkbox"
                                   id="banned"
                                   wire:model.defer="user.banned">

                            @error('user.banned')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer card-footer">
                        <button type="button" class="btn btn-secondary" @click="open = false">
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
