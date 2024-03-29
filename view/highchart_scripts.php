<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>

<script>
$(document).ready(function () {

	$('#highchart-container-downloads-by-installer-and-php-version').highcharts({
		chart: {
			type: 'column'
		},
		title: {
	        text: 'Downloads'
	    },
	    subtitle: {
	    	text: 'Number of Installation Wizard Downloads by Installer Version with PHP Version share'
	    },
        data: {
	    	table: 'downloads-by-installer-and-php-version'
	    },
	    plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                }
            }
        },
        tooltip: {
            formatter: function () {
                return 'WPN-XM <b>' + this.key + '</b> had <br/>' +
                     '<b>' + this.y + '</b> downloads of Installation Wizards shipping <b>'+ this.series.name + '</b>.<br/>' +
                     'That are <b>' + this.point.percentage.toFixed(2) + '%</b> of the total downloads of ' + this.point.stackTotal + '.';
            }
        },
        yAxis: {
        	min: 0,
            title: {
                text: 'Downloads'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        credits: {
            text: 'wpn-xm.org',
            href: 'http://wpn-xm.org/'
        },
    });

    $('#highchart-container-total-downloads').highcharts({
        title: {
            text: 'Downloads'
        },
        subtitle: {
            text: 'Number of Installation Wizard Downloads by Installer Version'
        },
        chart: {
        	type: 'spline'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Downloads'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        data: {
            table: 'total-downloads-by-installer-version',
            switchRowsAndColumns: 1
        },
        legend: {
        	enabled: 0
        },
        credits: {
            text: 'wpn-xm.org',
            href: 'http://wpn-xm.org/'
        },
    });

    $('#highchart-container-downloads-by-php-version').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 0,
            plotShadow: false
        },
        title: {
            text: 'Downloads<br>by<br>PHP Version',
            align: 'center',
            verticalAlign: 'middle',
            y: 50
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b><br>That are <b>{point.percentage:.1f}%</b> of the total downloads.</b>'
        },
        plotOptions: {
            pie: {
                dataLabels: {
                    enabled: true,
                    distance: -50,
                    style: {
                        fontWeight: 'bold',
                        color: 'white',
                        textShadow: '0px 1px 2px black'
                    }
                },
                startAngle: -90,
                endAngle: 90,
                center: ['50%', '75%']
            }
        },
        series: [{
            type: 'pie',
            name: 'Installer Downloads with this PHP Version',
            innerSize: '50%',
            data: [
                <?php echo HighchartHelper::render_json_for_phpversions_piechart($s['downloads_by_php_version']); ?>
            ]
        }],
        credits: {
            text: 'wpn-xm.org',
            href: 'http://wpn-xm.org/'
        },
    });

});
</script>