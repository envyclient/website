@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">
        <!-- versions table -->
        <div>
            <div class="alert alert-primary" style="font-size:25px;">
                Versions
            </div>
            <div style="height: 300px">
                {!! $chart->container() !!}
            </div>

            @livewire('versions-table')
        </div>

        <br>

        <!-- create version -->
        <div>
            <div class="alert alert-secondary" style="font-size:25px;">
                Create Version
            </div>

            <form action="{{ route('admin.versions.upload') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input class="form-control @error('name') is-invalid @enderror"
                           placeholder="Name"
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
            </form>

            <br>

        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
@endsection
