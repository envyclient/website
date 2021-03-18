@extends('layouts.dash')

@section('title', 'Versions')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!-- versions table -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Versions
            </div>

            @livewire('version.list-versions')
        </div>

        <br>

        <!-- create version -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Create Version
            </div>

            @livewire('upload-version')
        </div>
    </div>


@endsection
