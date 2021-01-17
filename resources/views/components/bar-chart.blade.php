@props([
    'name',
    'type' => 'text'
])

<div id="chart" style="height: 300px;"></div>

@section('js')
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <script>
        const chart = new Chartisan({
            el: "#chart",
            url: "{{ url("api/chart/$name") }}",
            hooks: new ChartisanHooks()
                .tooltip(true)
                .legend(true)
                .datasets([
                    {
                        "stack": "stackbar",
                    }
                ])
                .options({
                    grid: {
                        top: 22,
                        bottom: 25,
                        left: '2%',
                        right: '2%',
                    }
                })
        });
    </script>
@endsection
