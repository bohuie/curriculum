<!DOCTYPE html>
<html>
<head>
    <title>Laravel 10 Highcharts Example - Tutsmake.com</title>
</head>
   
<body>
<h1>Laravel 10 Highcharts Example - Tutsmake.com</h1>
<div id="container"></div>
<button id="button">Export chart</button>
</body>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var users =  <?php echo json_encode($users) ?>;
    var title =  <?php echo json_encode($title) ?>;
    var xLabel = <?php echo json_encode($xLabel)?>;
    var yLabel = <?php echo json_encode($yLabel)?>;
    var hasLegend = <?php echo json_encode($hasLegend)?>;
    var categories = <?php echo json_encode($categories)?>;
    var data = <?php echo json_encode($data)?>;
   
    const chart = Highcharts.chart('container', {
        title: {
            text: title
        },
         xAxis: {
            title: {
                text: xLabel,
                margin: 20
            },
            style:{
                fontWeight:"bold"
            },
            categories: categories
        },
        yAxis: {
            title: {
                text: yLabel,
                margin: 20
            },
            allowDecimals: false
        },
        legend: {
            enabled: hasLegend
        },
        series: data
});

document.getElementById('button').addEventListener('click', () => {
    chart.exportChart();
});
</script>
</html>