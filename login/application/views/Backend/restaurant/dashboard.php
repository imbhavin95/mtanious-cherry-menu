<?php
if (isset($json)) {
        echo "<script> var data=" . $json . "</script>";
    } else {
        echo "<script> var data=''</script>";
    }

if (isset($json1)) {
        echo "<script> var data1=" . $json1 . "</script>";
    } else {
        echo "<script> var data1=''</script>";
    }
    $get_date = $this->input->get('date');
    $start_date = '';
    $end_date = '';
    if ($get_date != '') {
        $dates = explode('-', $get_date);
        $start_date = date('F j, Y', strtotime(@$dates[0]));
        $end_date = date('F j, Y', strtotime(@$dates[1]));
    }

     if($this->session->userdata('login_user')['id'] !="" && !empty($this->session->userdata('login_user')['id'])){
    $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
  foreach ($query->result() as $row) {?>
     <?php $latesttimestamp = $row->latesttimestamp;?>
<?php }
}
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
        <li class="col-xs-6 col-lg-2">
            <span class="title"><i class="fa fa-users"></i> Users</span>
            <h3 class="color-up"><?php echo isset($total_users) ? $total_users : 0; ?></h3>
           
        </li>
        <li class="col-xs-6 col-lg-2">
            <span class="title"><i class="fa fa-cutlery"></i> Menus</span>
            <h3><?php echo isset($total_menus) ? $total_menus : 0; ?></h3>
           
        </li>
        <li class="col-xs-6 col-lg-2">
            <span class="title"><i class="fa fa-list"></i> Categories</span>
            <h3><?php echo isset($total_categories) ? $total_categories : 0 ; ?></h3>
            
        </li>
        <li class="col-xs-6 col-lg-2">
            <span class="title"><i class="fa fa-list"></i> Items</span>
            <h3><?php echo isset($total_items) ? $total_items : 0; ?></h3>
            
        </li>
        <li class="col-xs-6 col-lg-2">
            <span class="title"><i class="fa fa-qrcode"></i> QR Code</span>
            <?php if(isset($sesurl) && !empty($sesurl)){ ?>
             <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $sesurl;?>&amp;size=150x150" alt="Web Link Not Generated" title="" />
            <?php }else{?>
                <br>
            <span>Please go to background settings and give restaurant name,so QR Code will be generated</span>
            <?php }?>
        </li>
    </ul>
 </div>
<!-- End Top Stats -->
<!-- Start First Row -->

  
    <!-- <div class="col-lg-6 col-md-6 left-graph">
      <div class="panel panel-default">
        <div class="panel-title">
          Item's
        </div>
            <div class="panel-body">
              <div id="items_chart" class="ct-chart ct-perfect-fourth">
              
              </div>
            </div>
      </div>
    </div> -->
    <!-- End Chart -->
    
    <!-- Start Chart -->
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-title">
         Line chart of click
          <button type="button" class="btn bg-slate-400 daterange-ranges" id="date_range_pick">
                        <i class="icon-calendar22 position-left"></i><span>Select Date Range to filter data</span><b class="caret"></b>
                    </button>
        </div>
            <div class="panel-body">
              <div id="items_click_chart" class="ct-chart ct-perfect-fourth"  style="height: 373px;">
              
              </div>
            </div>
      </div>
    </div>
</div>
<!-- main file -->
<script type="text/javascript" src="assets/Backend/js/highcharts/highcharts.js"></script>
<script type="text/javascript" language="javascript">


    //Item Clicks Chart
    if (data != "") 
    {
        Xindex = [], Yindex1 = [], Yindex2 = [];
        $.each(data.key_array, function (i, v) {
            Xindex.push(v[1]);
        });

        $.each(data.key_array, function (i, v) {
            if (data.items_click[v[0]] != undefined) {
                var obj = data.items_click[v[0]];
                Yindex1.push(obj[0]);
            } else {
                Yindex1.push(0);
            }
        });
    }
    //Categories Clicks Chart
    if (data1 != "") 
    {
        Xindex1 = [], Yindex_1 = [], Yindex_2 = [];
        $.each(data1.key_array1, function (i, v) {
            Xindex1.push(v[1]);
        });

        $.each(data1.key_array1, function (i, v) {
            if (data1.category_click[v[0]] != undefined) {
                var obj = data1.category_click[v[0]];
                Yindex_1.push(obj[0]);
            } else {
                Yindex_1.push(0);
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
    Highcharts.chart('items_click_chart', {
        title: {
            text: 'Line chart of click'
        },
        xAxis: {
        categories: Xindex
    },
        yAxis: {
        title: {
            text: 'Number of Click\'s'
        }},
        legend : {
               layout: 'vertical',
               align: 'right',
               verticalAlign: 'middle',
               borderWidth: 0,
        },
        series : [{
                  name: ['Item Click\'s'],
                  data: Yindex1.map(Number)
            },
            {
                  name: ['Category Click\'s'],
                  data: Yindex_1.map(Number)
            },
        ]
    });

</script>
<script src="https://www.gstatic.com/firebasejs/5.8.1/firebase.js"></script>
<input type="hidden" name="latesttimestamp" id="latesttimestamp" value="<?php echo $latesttimestamp; ?>">
<script type="text/javascript">
    $( document ).ready(function() {
     //   alert('hello');
      // Initialize Firebase
  var config = {
    apiKey: "AIzaSyB6Y0E1yzKwk00C_ks7YsXwTHkaZJuFSM0",
    authDomain: "cherrymenu-44b6e.firebaseapp.com",
    databaseURL: "https://cherrymenu-44b6e.firebaseio.com",
    projectId: "cherrymenu-44b6e",
    storageBucket: "cherrymenu-44b6e.appspot.com",
    messagingSenderId: "832799477411"
  };
  firebase.initializeApp(config);
        var restaurantid = <?php echo $this->session->userdata('login_user')['id'];?>;
        var latesttimestamp = $("#latesttimestamp").val();
       // alert(restaurantid,latesttimestamp);
        if(restaurantid !="" && latesttimestamp !=""){
            insertData(restaurantid, latesttimestamp);
        }
        insertData(restaurantid, latesttimestamp);
        function insertData(restaurantid, latesttimestamp) {
            firebase.database().ref('Restaurants/' + restaurantid).set({
                LastUpdatedTime: latesttimestamp,
            });          
        }
        });
    </script>