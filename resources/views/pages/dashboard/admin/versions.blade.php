@extends('layouts.dash')

@section('title', 'Versions')

@section('content')

    <section>
        @livewire('admin.version.versions-table')
    </section>

    <br>

    <div>
        <div class="alert alert-dark fs-4">
            Create Version
        </div>

        @livewire('admin.version.upload-version')
    </div>

    <section>
        @livewire('admin.upload-launcher')
    </section>
@endsection
