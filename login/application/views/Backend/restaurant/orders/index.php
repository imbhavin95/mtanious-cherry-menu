<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li class="active">Orders</li>
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
                        <h4 class="modal-title">View Orders</h4>
                    </div>
                    <div id="restaurantbody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="device_enable" style="display:none">
        <div id="dev_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="device_disable" style="display:none">
        <div id="dev_disable"></div>
    </div>
    <div class="panel panel-default">
        <div style="text-align: right; margin-bottom: 10px;">
           <!-- <a href="<?php //echo base_url('restaurant/orders/orderreport'); ?>" class="btn btn-default">Items Report</a> --> 
        </div>
        <div class="panel-body table-responsive">
            <table id="devices" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Id</th>
                        <th>Staff</th>
                        <th>Ordered On</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script>
    $(document).ready(function() {
        $(function () {
            $('#devices').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                order: [[4, "desc"]],
                ajax: '<?php echo base_url("restaurant/orders/get_orders"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "order_id",
                        visible: true,
                        searchable: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "staff_name",
                        visible: true,
                        searchable: true,
                        render: $.fn.dataTable.render.text()
                    },                  
                    {
                        data: "ordered_time",
                        visible: true,
                    },
                    {
                        data: "is_deleted",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var action = '';
                            var deleteurl = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                action +='<a href="javascript:void(0)" class="view_btn icons-design" id="' + btoa(full.order_id) + '" data-id=' + btoa(full.order_id) + '><img src="<?php echo base_url('public/Eye.svg'); ?>"></a>'
                                action +='</div>'
                            return action;
                        }
                    }
                ]
            });
        });

    });

    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("restaurant/orders/view_order"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#restaurantbody').html(response);
                $('#myModal').modal('show');
            }
        });
    });
</script>