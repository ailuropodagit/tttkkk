<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/modules/exporting.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var options = {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Analysis Report'
            },
            subtitle: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            exporting: {
                enabled: false
            },
            plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y:.0f}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                 showInLegend: true
                }
            },
            series: []
        }

        var the_year = $('#the_year').val();
        var the_month = $('#the_month').val();

        var post_url1 = 'http://' + $(location).attr('hostname') + '/keppo/admin/getChart_Merchant';
        $.ajax({
            type: "POST",
            url: post_url1,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month,
            success: function (return_data) {
                options.series.length = 0;
                options.title.text = 'New/Old Merchant Active Analaysis Report';
                
                var cutoff_total = 0;
                for (var i = 0; i < return_data.length; i++) {
                    cutoff_total += return_data[i]['y'];
                }
                options.subtitle.text = 'Total merchent until this period: ' + cutoff_total;
                
                options.chart.renderTo = 'container_merchant';
                //alert(JSON.stringify(return_data));
                options.series = [{name: 'Percentage', data: return_data}]
                chart = new Highcharts.Chart(options);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //alert(textStatus);
                //alert(errorThrown);
            }
        });

    });
</script>

<!-- Dunno why cannot put on top, if put on top then the chart is not working -->
<script type="text/javascript" src="<?php echo base_url() ?>js/js_custom.js"></script> 

<div id="analysis-report">
    <h1>Insights</h1>
    <div id="analysis-report-content">
        <div id="candie-navigation">
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/analysis_report"; ?>" >Merchant Report</a></div>
            <div id='candie-navigation-each-separator'>|</div>
            <div id='candie-navigation-each'><a href="<?php echo base_url() . "admin/analysis_report_user";; ?>" >User Report</a></div>
        </div>
        <div id="float-fix"></div><br/><br/>
        <div id="analysis-report-go">
            <?php echo form_open(uri_string()) ?>
            <span id="analysis-report-go-each">Filter :</span>
            <span id="analysis-report-go-each"><?php echo form_dropdown($the_year, $year_list, $the_year_selected); ?></span>
            <span id="analysis-report-go-each"><?php echo form_dropdown($the_month, $month_list, $the_month_selected); ?></span>
            <span id="analysis-report-go-each"><button name="button_action" type="submit" value="search_history">Go</button></span>
            <?php echo form_close() ?>
        </div>
        
        <div id='analysis-report-print'>
            <a href="#" onclick="printDiv('print-area')"><i class="fa fa-print"></i> Print Report</a>
        </div>
        <div id="analysis-report-period">
            Report Period As New Merchant : <?php echo $first_day ?> to <?php echo $last_day ?>
        </div>
        <div id='float-fix'></div>
        
        <div id="print-area">
            <div id="container_merchant" style="min-width: 400px; height: 400px; margin: 0 auto"></div><br/><br/><br/>
        </div>
        
    </div>
</div>