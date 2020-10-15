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

            @livewire('upload-version')

        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
@endsection
