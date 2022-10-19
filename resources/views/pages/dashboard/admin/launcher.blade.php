@extends('layouts.dash')

@section('title', 'Launcher & Assets')

@section('content')

    <section>
        @livewire('admin.upload-launcher')
    </section>

    <section class="mt-4">
        @livewire('admin.upload-assets')
    </section>

@endsection
