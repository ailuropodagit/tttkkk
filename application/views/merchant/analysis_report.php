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
                text: 'Gender Analysis Report'
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
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
            },
            exporting: {
                enabled: false
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

        var the_year = $('#the_year').val();
        var the_month = $('#the_month').val();
        var the_adv_type = $('#the_adv_type').val();
        
        var post_url = 'http://' + $(location).attr('hostname') + '/keppo/merchant/getChart_gender';
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month + "&the_adv_type=" + the_adv_type,
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
<?php echo form_open_multipart(uri_string()); ?>
<div id="candie-promotion-form-go">
                <span id="candie-promotion-form-go-label"><?php echo "Filter "; ?></span>
                <span id="candie-promotion-form-go-year"><?php echo form_dropdown($the_year, $year_list, $the_year_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_adv_type, $adv_type_list, $the_adv_type_selected); ?></span>
                <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_history">Go</button></span>
            </div>
        <?php echo form_close(); ?>
        <div id="container_gender" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
        
   