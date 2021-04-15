@extends('layouts.dash')

@section('title', 'Versions')

@section('content')

    <section>
        @livewire('admin.version.versions-table')
    </section>

    <section class="mt-4">
        @livewire('admin.version.upload-version')
    </section>

    <section class="mt-4">
        @livewire('admin.upload-launcher')
    </section>
@endsection
