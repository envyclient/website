@extends('layouts.dash')

@section('content')
    <div style="width:98%;margin:0 auto">

        <!-- users chart -->
        <div>
            <div class="alert alert-dark" style="font-size:25px;">
                Stats
            </div>
            <div id="chart" style="height: 300px;"></div>
        </div>

        <!-- users table -->
        <div id="users-table">
            <div class="alert alert-dark" style="font-size:25px;">
                User Management
                <span class="badge bg-secondary mx-2">
                    {{ \App\Models\User::count() }}
                </span>
                <span class="badge bg-secondary mx-2">
                    {{ \App\Models\User::where('current_account', '<>', null)->count() }}
                </span>
            </div>

            @livewire('users-table')
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <script>
        const chart = new Chartisan({
            el: '#chart',
            url: "@chart('users_chart')",
            hooks: new ChartisanHooks()
                .tooltip(true)
                .datasets([
                    {
                        "stack": "stackbar"
                    }
                ])
                .options({
                    grid: {
                        top: 6,
                        bottom: 25,
                        left: '3%',
                        right: '3%',
                    }
                })
        });
    </script>
@endsection
