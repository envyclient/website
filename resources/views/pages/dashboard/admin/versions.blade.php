@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!-- versions table -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Versions
            </div>
            <x-bar-chart name="users_chart"/>

            @livewire('version.list-versions')
        </div>

        <br>

        <!-- create version -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Create Version
            </div>

            <form action="{{ route('admin.versions.upload') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control @error('name') is-invalid @enderror"
                           type="text"
                           id="name"
                           name="name"
                           required>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="changelog">Changelog</label>
                    <textarea class="form-control" id="changelog" name="changelog" rows="3" required></textarea>

                    @error('changelog')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" value="" id="beta" name="beta">
                    <label class="form-check-label" for="beta">
                        Is Beta Version?
                    </label>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Version</label>
                    <input class="form-control @error('version') is-invalid @enderror"
                           type="file"
                           id="version"
                           name="version"
                           accept=".exe"
                           required>

                    @error('version')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Assets</label>
                    <input class="form-control @error('assets') is-invalid @enderror"
                           type="file"
                           id="assets"
                           name="assets"
                           accept=".jar"
                           required>

                    @error('assets')
                    <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Upload</button>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                        data-bs-target="#update-launcher">
                    Update Launcher
                </button>
            </form>

            <br>

        </div>
    </div>

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
@endsection
