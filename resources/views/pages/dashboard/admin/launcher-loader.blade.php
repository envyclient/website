@extends('layouts.dash')

@section('title', 'Launcher, Assets & Loader')

@section('content')

    <section>
        @livewire('admin.upload-launcher')
    </section>

    <section class="mt-4">
        @livewire('admin.upload-assets')
    </section>

    <section class="mt-4">
        @livewire('admin.upload-loader')
    </section>

@endsection
