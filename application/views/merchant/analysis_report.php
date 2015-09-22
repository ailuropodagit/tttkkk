<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/modules/exporting.js"></script>

<script type="text/javascript">
        $(document).ready(function() {
       var options ={
        chart: {
                type: 'bar'
            },
            title: {
                text: 'Analysis Report'
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
                        enabled: true,
                    }
                }
            },
            series: []
        }

        var the_year = $('#the_year').val();
        var the_month = $('#the_month').val();
        var the_adv_type = $('#the_adv_type').val();
        var the_new_user = $('#the_new_user').val();
        
        var post_url = 'http://' + $(location).attr('hostname') + '/keppo/merchant/getChart_gender';
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month + "&the_adv_type=" + the_adv_type + "&the_new_user=" + the_new_user,
            success: function (data) {
                options.series.length = 0;
                options.title.text = 'Gender Analysis Report';
                options.yAxis.title.text = 'Gender';
                options.chart.renderTo = 'container_gender';               
                options.series[0] = data[0];
                options.series[1] = data[1];
                
                chart = new Highcharts.Chart(options);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //alert(textStatus);
                //alert(errorThrown);
            }
        });

        var post_url2 = 'http://' + $(location).attr('hostname') + '/keppo/merchant/getChart_race';
        $.ajax({
            type: "POST",
            url: post_url2,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month + "&the_adv_type=" + the_adv_type + "&the_new_user=" + the_new_user,
            success: function (data) {
                options.series.length = 0;
                options.title.text = 'Race Analysis Report';
                options.yAxis.title.text = 'Race';
                options.chart.renderTo = 'container_race';    
                options.series[0] = data[0];
                options.series[1] = data[1];
                options.series[2] = data[2];
                options.series[3] = data[3];
                
                chart = new Highcharts.Chart(options);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //alert(textStatus);
                //alert(errorThrown);
            }
        });

        var post_url3 = 'http://' + $(location).attr('hostname') + '/keppo/merchant/getChart_age';
        $.ajax({
            type: "POST",
            url: post_url3,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month + "&the_adv_type=" + the_adv_type + "&the_new_user=" + the_new_user,
            success: function (data) {
                options.series.length = 0;
                options.title.text = 'Age Analysis Report';
                options.yAxis.title.text = 'Age';
                options.chart.renderTo = 'container_age';             
                options.series[0] = data[0];
                options.series[1] = data[1];
                options.series[2] = data[2];
                options.series[3] = data[3];
                options.series[4] = data[4];
                
                chart = new Highcharts.Chart(options);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //alert(textStatus);
                //alert(errorThrown);
            }
        });
        
    });
</script>
        
    <h1>Analysis Report</h1>
<?php echo form_open(uri_string());?>
<div id="candie-promotion-form-go">
                <span id="candie-promotion-form-go-label"><?php echo "Filter "; ?></span>
                <span id="candie-promotion-form-go-year"><?php echo form_dropdown($the_year, $year_list, $the_year_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_adv_type, $adv_type_list, $the_adv_type_selected); ?></span>
                <span id="candie-promotion-form-go-month"><?php echo form_dropdown($the_new_user, $new_user_list, $the_new_user_selected); ?></span>
                <span id="candie-promotion-form-go-button"><button name="button_action" type="submit" value="search_history">Go</button></span>
            </div>
        <?php echo form_close(); ?>
    <div style="float:right"><?php echo 'Report Period : ' . $first_day . " to " . $last_day; ?></div>
    <div id="container_gender" style="min-width: 400px; height: 400px; margin: 0 auto"></div><br/><br/><br/>
        
        <div id="container_race" style="min-width: 400px; height: 400px; margin: 0 auto"></div><br/><br/><br/>
        
             <div id="container_age" style="min-width: 400px; height: 400px; margin: 0 auto"></div><br/><br/><br/>