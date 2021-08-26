@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h2 class="text-center"><strong>Number of Course Outcomes</strong></h2>
    <div id="high-chart"></div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">

document.addEventListener('DOMContentLoaded', function () {
        const chart = Highcharts.chart('high-chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Fruit Consumption'
            },
            xAxis: {
                categories: ['Apples', 'Bananas', 'Oranges']
            },
            yAxis: {
                title: {
                    text: 'Fruit eaten'
                }
            },
            series: [{
                name: 'Jane',
                data: [1, 0, 4]
            }, {
                name: 'John',
                data: [5, 7, 3]
            }]
        });
    });
</script>
<style>
    .highcharts-credits {
        display: none;
    }
</style>
@endsection