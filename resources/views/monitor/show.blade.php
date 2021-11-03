@extends('layouts.app')

@section('title', $monitor->name)

@section('content')
    <script>
        var cpu_lable = 'CPU(%)',
            mem_lable = 'Memory(%)',
            disk_lable = 'Disk usage (%)',
            upload_speed_lable = 'Upload speed (kb)',
            download_speed_lable = 'Download speed (kb)';
        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };
    </script>

    <h1 class="mdui-text-center mdui-typo-headline mdui-typo">{{ $monitor->name }}</h1>
    <div class="mdui-row">
        <div class="mdui-col-xs-6">
            <h1 class="mdui-text-center mdui-typo-title">资源监控</h1>

            <canvas id="canvas_{{ $monitor->id }}"></canvas>
        </div>
        <div class="mdui-col-xs-6">
            <h1 class="mdui-text-center mdui-typo-title">网络活动</h1>
            <canvas id="canvas_{{ $monitor->id }}_network"></canvas>
        </div>
    </div>
    <script>
        var config = {
            type: 'line',
            data: {
                labels: [@foreach ($monitor->data as $key) '{{ $key->created_at }}', @endforeach],
                datasets: [{
                    label: cpu_lable,
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
                        @foreach ($monitor->data as $key) '{{ $key->cpu_usage }}', @endforeach
                    ],
                    fill: false,
                }, {
                    label: mem_lable,
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [
                        @foreach ($monitor->data as $key) '{{ $key->mem_usage }}', @endforeach
                    ],
                }, {
                    label: disk_lable,
                    fill: false,
                    backgroundColor: window.chartColors.orange,
                    borderColor: window.chartColors.orange,
                    data: [
                        @foreach ($monitor->data as $key) '{{ $key->disk_usage }}', @endforeach
                    ],
                }]
            }
        };

        var ctx = document.getElementById('canvas_{{ $monitor->id }}').getContext('2d');
        new Chart(ctx, config);

        var config = {
            type: 'line',
            data: {
                labels: [@foreach ($monitor->data as $key) '{{ $key->created_at }}', @endforeach],
                datasets: [{
                    label: download_speed_lable,
                    fill: false,
                    backgroundColor: window.chartColors.purple,
                    borderColor: window.chartColors.purple,
                    data: [
                        @foreach ($monitor->data as $key) '{{ $key->download_speed }}', @endforeach
                    ],
                }, {
                    label: upload_speed_lable,
                    fill: false,
                    backgroundColor: window.chartColors.yellow,
                    borderColor: window.chartColors.yellow,
                    data: [
                        @foreach ($monitor->data as $key) '{{ $key->upload_speed }}', @endforeach
                    ],
                }]
            }
        };

        var ctx = document.getElementById('canvas_{{ $monitor->id }}_network').getContext('2d');
        new Chart(ctx, config);
    </script>


    <script>
        var url = window.location.href
        var timer = setInterval(function() {
            if (window.location.href == url) {
                $.pjax.reload('.pjax-container')
                clearInterval(timer)
            } else {
                clearInterval(timer)
            }
        }, 5000)
    </script>

@endsection
