<div class="mt-3">
    <label class="form-label">Cape</label>
    <br>
    <img src="{{ $user->cape }}"
         alt="cape"
         class="rounded"
         width="256px"
         height="128px">

    <form wire:submit.prevent="submit">
        <div class="mb-3">
            <x-filepond wire:model="cape"
                        accept="image/png"
                        maxFileSize="1024KB"
                        maxImageWidth="2048"
                        maxImageHeight="1024"
                        required
            />
            <div class="form-text">
                The dimensions must be 2048x1024.
            </div>
        </div>
        <button type="submit" class="btn btn-success inline">Upload Cape</button>
        <button class="btn btn-outline-danger" wire:click="resetCape">Reset Cape</button>
        <a class="btn btn-outline-dark" href="{{ asset('assets/capes/template.png') }}">
            Download Template
        </a>
    </form>
</div>
