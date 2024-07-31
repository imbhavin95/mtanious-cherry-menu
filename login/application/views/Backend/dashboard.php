<?php
if (isset($json)) {
        echo "<script> var data=" . $json . "</script>";
    } else {
        echo "<script> var data=''</script>";
    }
    $get_date = $this->input->get('date');
    $start_date = '';
    $end_date = '';
    if ($get_date != '') {
        $dates = explode('-', $get_date);
        $start_date = date('F j, Y', strtotime(@$dates[0]));
        $end_date = date('F j, Y', strtotime(@$dates[1]));
    }
?>
    <div class="page-header">
        <h1 class="title"><?php echo $head; ?></h1>
        <ol class="breadcrumb">
            <li class="active">Dashboard</li>
        </ol>
    </div>
<div class="container-widget">
    <!-- Start Top Stats -->
    <div class="col-md-12">
        <ul class="topstats clearfix">
            <li class="arrow"></li>
            <li class="col-xs-6 col-lg-3">
                <span class="title"><i class="fa fa-cutlery"></i> Restaurant</span>
                <h3><?php echo isset($total_restaurant) ? $total_restaurant : 0; ?></h3>
            </li>
            <li class="col-xs-6 col-lg-3">
                <span class="title"><i class="fa fa-users"></i> Users</span>
                <h3><?php echo isset($total_users) ? $total_users : 0 ; ?></h3>
            </li>
            <li class="col-xs-6 col-lg-3">
                <span class="title"><i class="fa fa-list"></i> Categories</span>
                <h3><?php echo isset($total_categories) ? $total_categories : 0; ?></h3>
            </li>
            <li class="col-xs-6 col-lg-3">
                <span class="title"><i class="fa fa-list"></i> Items</span>
                <h3><?php echo isset($total_items) ? $total_items : 0; ?></h3>
            </li>
        </ul>
    </div>
    <!-- End Top Stats -->
    <!-- Start First Row -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class=" panel-widget widget chart-with-stats clearfix">
                <!-- Start Chart -->
        
        <!-- End Chart -->
            </div>
        </div>
    </div>
    <div class="row d-flex mb-10 flex_none">    
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-title">
                    Restaurants
                    <button type="button" class="btn bg-slate-400 daterange-ranges" id="date_range_pick">
                        <i class="icon-calendar22 position-left"></i><span>Select Date Range to filter data</span><b class="caret"></b>
                    </button>
                </div>
                    <div class="panel-body">
                        <div id="restaurant_chart" class="ct-chart ct-perfect-fourth">
                        
                        </div>
                    </div>
            </div>
        </div>
        <!-- End Chart -->        
        <!-- Start Chart -->
        <div class="col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-title">
                Items
                </div>
                    <div class="panel-body">
                        <div id="items_chart" class="ct-chart ct-perfect-fourth">
                        
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/Backend/js/highcharts/highcharts.js"></script>
<script type="text/javascript" language="javascript">
    if (data != "") 
    {
        Xindex = [], Yindex1 = [], Yindex2 = [];
        $.each(data.key_array, function (i, v) {
            Xindex.push(v[1]);
        });

        $.each(data.key_array, function (i, v) {
            if (data.restaurant[v[0]] != undefined) {
                var obj = data.restaurant[v[0]];
                Yindex1.push(obj[0]);
            } else {
                Yindex1.push(0);
            }
        });
    }

    $(function () 
    {
        var get = '<?php echo $this->input->get('date') ?>';
        if (get != "") {
            var res = get.split('-');
            start = res[0];
            end = res[1];

            $("#date_range_pick").daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                alwaysShowCalendars: true,
            },
                    function (start, end) {
                        $('.daterange-ranges span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
                    }
            );
            $('#date_range_pick span').html('<?php echo $start_date ?>' + ' &nbsp; - &nbsp; ' + '<?php echo $end_date ?>');

        } else {
           $("#date_range_pick").daterangepicker({
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
                autoUpdateInput: false,
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                alwaysShowCalendars: true,
            },
                    function (start, end) {
                        $('.daterange-ranges span').html(moment().startOf('month').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().endOf('month').format('MMMM D, YYYY'));
                    }
            );
            $('#date_range_pick span').html(moment().startOf('month').format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + moment().endOf('month').format('MMMM D, YYYY'));

        }

        $('#date_range_pick').on('apply.daterangepicker', function (ev, picker) {
            var url = window.location.href;
            var newurl = updateQueryStringParameter(url, "date", picker.startDate.format('MM/DD/YYYY') + '-' + picker.endDate.format('MM/DD/YYYY'));
            $('#date_range_pick span').html(picker.startDate.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + picker.endDate.format('MMMM D, YYYY'));
            window.location.href = newurl;
        });
        $('#date_range_pick').on('cancel.daterangepicker', function (ev, picker) {
            if ($('#date_range_pick span').html() != '') {
                var url = window.location.href;
                var newurl = updateQueryStringParameter(url, "date", '');
                window.location.href = newurl;
            }
            $('#date_range_pick span').html('');
        });

        $('#date_range_pick').on('cancel.daterangepicker', function (ev, picker) {
            $('date_range_pick span').html('');
        });

    });

    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }

    // Restaurant Chart
    Highcharts.chart('restaurant_chart', {
        title: {
            text: 'Restaurant\'s'
        },
        xAxis : {
            categories: Xindex
        },
        yAxis: {
        title: {
            text: 'Number of Restaurant'
        }},
        legend : {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0
        },
        series : [{
                  name: ['Restaurant'],
                  data: Yindex1.map(Number)
            },
        ]
    });

    // Item Chart
    $pie = JSON.parse('<?php echo json_encode($type); ?>');
    $merge = [];
    $.each($pie, function (i, v) {
        $merge.push([i,v]);
        });

    Highcharts.chart('items_chart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Items'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Items',
            colorByPoint: true,
            data: $merge
        }]
    });
</script>
