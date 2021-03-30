@extends('layouts.dash')

@section('title', 'Versions')

@section('content')
    <div>
        <div class="alert alert-dark fs-4">
            Versions
        </div>

        @livewire('version.list-versions')
    </div>

    <br>

    <div>
        <div class="alert alert-dark fs-4">
            Create Version
        </div>

        @livewire('upload-version')
    </div>
@endsection
