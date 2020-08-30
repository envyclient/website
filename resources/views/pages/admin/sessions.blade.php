@extends('layouts.dash')

@section('content')
    <div style="width:95%;margin:0 auto">
        <div class="alert alert-primary" style="font-size:25px;">
            Game Sessions
        </div>
        <div class="row">
            <div class="col" style="height: 700px">
                {!! $gameSessionsChart->container() !!}
            </div>
            <div class="col" style="height: 700px">
                {!! $gameSessionsToggleChart->container() !!}
            </div>
        </div>
    </div>
@endsection

@section('js')
    {!! $gameSessionsChart->script() !!}
    {!! $gameSessionsToggleChart->script() !!}
@endsection
