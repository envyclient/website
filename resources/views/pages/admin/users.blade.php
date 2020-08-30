@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!-- users chart -->
        <div>
            <div class="alert alert-primary" style="font-size:25px;">
                Stats
            </div>
            <div style="height: 300px">
                {!! $chart->container() !!}
            </div>
        </div>

        <br>

        <!-- users table -->
        <div id="users-table">
            <div class="alert alert-secondary" style="font-size:25px;">
                User Management
            </div>
            <users-table url="{{ route('api.admin.users') }}"
                         api-token="{{ $apiToken }}">
            </users-table>
        </div>
    </div>
@endsection

@section('js')
    {!! $chart->script() !!}
@endsection
