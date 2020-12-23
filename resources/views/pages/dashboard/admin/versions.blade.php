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

                <div class="form-group">
                    <label for="name">Name</label>
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

                <div class="form-group">
                    <label for="changelog">Changelog</label>
                    <textarea class="form-control" id="changelog" name="changelog" rows="3" required></textarea>

                    @error('changelog')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="beta" name="beta">
                        <label class="custom-control-label" for="beta">Is Beta Version?</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Version</span>
                        </div>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('version') is-invalid @enderror"
                                   id="version"
                                   name="version"
                                   accept=".exe"
                                   required>
                            <label class="custom-file-label" for="version">Choose file</label>

                            @error('version')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Assets</span>
                        </div>
                        <div class="custom-file">
                            <input type="file"
                                   class="custom-file-input @error('assets') is-invalid @enderror"
                                   id="assets"
                                   name="assets"
                                   accept=".jar"
                                   required>
                            <label class="custom-file-label" for="assets">Choose file</label>

                            @error('assets')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Upload</button>
            </form>

            <br>

        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
    <script>
        document.getElementById('version').addEventListener('change', function (e) {
            const fileName = document.getElementById("version").files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
        document.getElementById('assets').addEventListener('change', function (e) {
            const fileName = document.getElementById("assets").files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>
@endsection
