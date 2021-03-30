@extends('layouts.dash')

@section('title', 'Versions')

@section('content')
    <div>
        <div class="alert alert-dark" style="font-size:25px;">
            Versions
        </div>

        @livewire('version.list-versions')
    </div>

    <br>

    <div>
        <div class="alert alert-dark" style="font-size:25px;">
            Create Version
        </div>

        @livewire('upload-version')
    </div>
@endsection
