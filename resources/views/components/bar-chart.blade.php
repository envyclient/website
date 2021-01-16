<div id="chart" style="height: 300px;"></div>

@section('js')
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
    <script>
        const chart = new Chartisan({
            el: '#chart',
            url: "@chart('{{ $name }}')",
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
