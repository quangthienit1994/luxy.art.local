<div id="high-line" class="chart"></div>

<script type="text/javascript">
    var use_point       = <?=$use_point?>;
    var buy_point       = <?=$buy_point?>;
    var list_category   = <?=$list_category?>;

    var line = $('#high-line');     
    if (line.length) {

        // High Line 3
        line.highcharts({
            credits: false,
            colors: highColors,
            chart: {
                backgroundColor: '#f9f9f9',
                className: 'br-r',
                type: 'line',
                zoomType: 'x',
                panning: true,
                panKey: 'shift',
                marginTop: 25,
                marginRight: 1,
            },
            title: {
                text: null
            },
            xAxis: {
                gridLineColor: '#EEE',
                lineColor: '#EEE',
                tickColor: '#EEE',
                categories: list_category
            },
            yAxis: {
                min: 0,
                tickInterval: 1000,
                gridLineColor: '#EEE',
                title: {
                    text: 'Lux',
                }
            },
            plotOptions: {
                spline: {
                    lineWidth: 3,
                },
                area: {
                    fillOpacity: 0.2
                }
            },
            legend: {
                enabled: false,
            },
            series: [{
                name: "<?php echo $this->lang->line('lux_used_by_the_user'); ?>",
                data: use_point
            }, {
                name: "<?php echo $this->lang->line('user_purchased_lux'); ?>",
                data: buy_point
            }]
        });

    }
</script>       

