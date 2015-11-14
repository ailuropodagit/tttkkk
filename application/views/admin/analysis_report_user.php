<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/highcharts/js/modules/exporting.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        var options = {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'New/Old User Gender Analysis Report'
        },
        subtitle: {
            text: ''
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }
        },
        exporting: {
                enabled: false
        },
        plotOptions: {
            pie: {
                shadow: false,
                center: ['50%', '50%'],
		allowPointSelect: true,
                cursor: 'pointer',
            }
        },
        tooltip: {
            valueSuffix: '%',
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        series: []
    }

        var the_year = $('#the_year').val();
        var the_month = $('#the_month').val();

        var post_url1 = 'http://' + $(location).attr('hostname') + '/keppo/admin/getChart_gender';
        $.ajax({
            type: "POST",
            url: post_url1,
            dataType: 'json',
            data: "&the_year=" + the_year + "&the_month=" + the_month,
            success: function (return_data) {
                options.series.length = 0;
                options.chart.renderTo = 'container_gender';
                
                var colors = Highcharts.getOptions().colors,
                categories = ['Old User', 'New User'],
                      data = [{
                                y: 14,
                                color: colors[0],
                                drilldown: {
                                    name: 'Old versions',
                                    categories: ['Chinese', 'Malay', 'India'],
                                    data: [4, 8, 2],
                                    color: colors[0]
                                }
                            }, {
                                y: 6,
                                color: colors[1],
                                drilldown: {
                                    name: 'New versions',
                                    categories: ['Chinese', 'Malay', 'India'],
                                    data: [3, 3, 0],
                                    color: colors[1]
                                }
                            }],
                browserData = [],
                versionsData = [],
                i,
                j,
                dataLen = data.length,
                drillDataLen,
                brightness;                
                        
                // Build the data arrays
                for (i = 0; i < dataLen; i += 1) {

                    // add browser data
                    browserData.push({
                        name: categories[i],
                        y: data[i].y,
                        color: data[i].color
                    });

                    // add version data
                    drillDataLen = data[i].drilldown.data.length;
                    for (j = 0; j < drillDataLen; j += 1) {
                        brightness = 0.2 - (j / drillDataLen) / 5;
                        versionsData.push({
                            name: data[i].drilldown.categories[j],
                            y: data[i].drilldown.data[j],
                            color: Highcharts.Color(data[i].color).brighten(brightness).get()
                        });
                    }
                }
                
                options.series = [{
                        name: 'Old/New',
                        data: browserData,
                        size: '60%',
                        dataLabels: {
                            formatter: function () {
                                return this.y > 5 ? this.point.name + ':</b> ' + this.y : null;
                            },
                            color: '#ffffff',
                            distance: -30
                        }
                    }, {
                        name: 'Gender',
                        data: versionsData,
                        size: '80%',
                        innerSize: '60%',
                        dataLabels: {
                            formatter: function () {
                                // display only if larger than 1
                                return this.y > 1 ? '<b>' + this.point.name + ':</b> ' + this.y : null;
                            }
                        }
                    }]

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
            Report Period : <?php echo $first_day ?> to <?php echo $last_day ?>
        </div>
        <div id='float-fix'></div>
        
        <div id="print-area">
            <div id="container_gender" style="min-width: 400px; height: 400px; margin: 0 auto"></div><br/><br/><br/>
        </div>
        
    </div>
</div>