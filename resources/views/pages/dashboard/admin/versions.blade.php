@extends('layouts.dash')

@section('title', 'Versions')

@section('content')
    <div>
        <div class="alert alert-dark fs-4">
            Versions
        </div>

        @livewire('admin.version.list-versions')
    </div>

    <br>

    <div>
        <div class="alert alert-dark fs-4">
            Create Version
        </div>

        @livewire('admin.version.upload-version')
    </div>
@endsection
