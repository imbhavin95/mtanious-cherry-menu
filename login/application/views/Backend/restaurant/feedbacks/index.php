<style>
    .dt-button {margin-left: 11px;width: 60px;height: 35px;background: #26A69A;padding: 6px;text-align: center;border-radius: 5px;color: white;font-weight: bold;border: 0px solid #fff;}
    .dt-buttons a:hover,.dt-buttons a:focus {color: #26A69A !important;background: #fff !important;border: 2px solid #26A69A;text-decoration:none;}
    .custom_perpage_dropdown .dataTables_length {margin: 0 18px 20px 20px;}
    .dataTables_info {padding: 8px 22px;margin-bottom: 10px;}
    .dataTables_paginate {margin: 10px 20px 20px 20px;}
</style>

<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li class="active">Feedbacks</li>
    </ol>
</div>
<div class="container-widget">
        <?php
            if (isset($error) && !empty($error)) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            if ($this->session->flashdata('success')) {
                echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
            }
            if ($this->session->flashdata('error')) {
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
            }
        ?>
    <!-- View Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">View Feedbacks</h4>
                    </div>
                    <div id="restaurantbody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->


    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="restaurant_enable" style="display:none">
        <div id="rest_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="restaurant_disable" style="display:none">
        <div id="rest_disable"></div>
    </div>

    <div class="panel panel-default">
        <div class="input-prepend input-group position-left" style="width: 200px;margin-left: auto;margin-right: 0;">
            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" id="date-range-picker" class="form-control" placeholder="Filter by date range"  /> 
        </div>         
    <!-- <button type="button" class="btn bg-slate-400 daterange-ranges" id="date_range_pick">
        <i class="icon-calendar22 position-left"></i><span>Select Date Range to filter data</span><b class="caret"></b>
    </button> -->

        <div class="panel-body table-responsive">
            <table id="feedback" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Staff Name</th>
                        <th>Feedback</th>
                        <th>Stars</th>
                        <th>Created On</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script type="text/javascript" src="assets/Backend/js/date-range-picker/daterangepicker.js"></script>
<script>
var startDate = null;
var endDate = null;
function bind()
{
    $('#feedback').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: 'lBfrtipx',
                buttons: [
                    {
                        extend: 'excel'
                    },
                    {
                        extend: 'pdf',
                        exportOptions: 
                        {
                            columns: [0,1,2,3,4,5],
                        },
                        customize : function(doc) 
                        {
                            doc.styles.tableHeader.alignment = 'left';
                            doc.content[1].table.widths = [ '5%', '20%','20%', '30%', '10%', '15%'];
                        }
                    }
                ],
                order: [[5, "desc"]],
                // ajax: '<?php //echo base_url("restaurant/feedbacks/get_feedbacks"); ?>',
                ajax: {
                    'url': '<?php echo base_url("restaurant/feedbacks/get_feedbacks"); ?>',
                    "data": {
                        'startDate': startDate,
                        'endDate' : endDate
                    },
                    "type": "GET"
                },
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "customer_name",
                        visible: true,
                        searchable: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "staff_name",
                        visible: true,
                        searchable: true,
                    },
                    {
                        data: "feedback",
                        visible: true,
                        searchable: true,
                        render: function ( data, type, row ) {
                            return data.length > 100 ?
                                data.substr( 0, 100 ) +'…' :
                                data;
                        }
                    },
                    {
                        data: "stars",
                        visible: true,
                        searchable: false,
                        sortable: true
                    },
                    {
                        data: "created_at",
                        visible: true,
                    },
                ]
            });
}

    $(document).ready(function() {
        bind();
        $('#date-range-picker').daterangepicker(null, function(start, end, label) 
        {
            console.log(start.format('D MM,YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            startDate = start.toISOString();
            endDate = end.toISOString();
            $("#feedback").dataTable().fnDestroy();
            bind();
        });
    });

     function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this feedback!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plz!"
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
        }, function (dismiss) {
            if (dismiss === 'cancel') {
                swal("Cancelled", "Your feedback is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.chkbox', function () {
        $('#restaurant_disable').hide();
        $('#restaurant_enable').hide();
        var feedback_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/feedbacks/change_status"); ?>',
            type: "POST",
            data: {id: feedback_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#restaurant_disable').show();
                    setTimeout(function(){ $('#restaurant_disable').hide() }, 3000);
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#restaurant_enable').show();
                    setTimeout(function(){ $('#restaurant_enable').hide() }, 3000);
                }
            }
        });
    });
</script>