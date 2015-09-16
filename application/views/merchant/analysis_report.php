<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/modules/exporting.js"></script>

<script type="text/javascript">
        $(document).ready(function() {
       var options ={
        chart: {
                renderTo: 'container_gender',
                type: 'bar'
            },
            title: {
                text: 'Hot Deal & Promotion Gender Analysis'
            },
            xAxis: {
                categories: ['View', 'Like', 'Rating', 'Redeem']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Gender'
                }
            },
            legend: {
                reversed: true
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                },
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: []
        }

        var post_url = 'http://' + $(location).attr('hostname') + '/keppo/merchant/getChart_gender';
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            success: function (json) {
                //options.xAxis.categories = json[0]['name'];
                options.series[0] = json[0];
                options.series[1] = json[1];

                chart = new Highcharts.Chart(options);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
                alert(errorThrown);
            }
        });


    });
</script>
        
    <h1>Analysis Report</h1>


        
        <div id="container_gender" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        
   