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
        <li class="active">Reports</li>
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
    <div class="panel panel-default">
    <div class="input-prepend input-group position-left" style="width: 200px;margin-left: auto;margin-right: 0;">
            <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
            <input type="text" id="date-range-picker" class="form-control" placeholder="Filter by date range"  /> 
        </div> 
        <div style="text-align: right; margin-bottom: 10px;">
        </div>
        <div class="panel-body table-responsive">
            <table id="items_click" class="table display nowrap drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Item</th>
                        <th>Item Type</th>
                        <!-- <th>Category</th> -->
                        <th>Number of Clicks</th>
                        <th>Clicked On</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/Backend/js/date-range-picker/daterangepicker.js"></script>
<script>
var startDate = null;
var endDate = null;
function bind()
{
    $('#items_click').dataTable({
                scrollX: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                "bPaginate": true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                order: [[5, "desc"]],
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
                            doc.content[1].table.widths = [ '5%', '20%','25%', '25%', '10%', '15%'];
                        }
                    }
                ],
                // ajax: '<?php //echo base_url("/restaurant/reports/get_reports"); ?>',
                ajax: {
                    'url': '<?php echo base_url("restaurant/reports/get_reports"); ?>',
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
                        data: "name",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "title",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "type",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "no_of_clicks",
                        visible: true,
                    },
                    {
                        data: "created_at",
                        visible: true,
                    } 
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
            $("#items_click").dataTable().fnDestroy();
            bind();
        });
    });
</script>
