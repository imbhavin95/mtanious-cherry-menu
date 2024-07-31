<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Packages</li>
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
                        <h4 class="modal-title">View Package</h4>
                    </div>
                    <div id="packagebody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->
    
        <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="package_enable" style="display:none">
            <div id="rest_enable"></div>
        </div>
        <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="package_disable" style="display:none">
            <div id="rest_disable"></div>
        </div>
    
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table id="packages" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Users Limit</th>
                        <th>Menus Limit</th>
                        <th>Categories Limit</th>
                        <th>Items Limit</th>
                        <th>Device Limit</th>
                        <th>Plan Duration</th>
                        <th>Upgrade</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script>

    $(document).ready(function() {
        var package = '<?php echo json_encode($package_details); ?>'; 
        $(function () {
            $('#packages').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                order: [[1, "desc"]],
                ajax: '<?php echo base_url("restaurant/packages/get_packages"); ?>',
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
                        data: "price",
                        visible: true,
                        render: function (data, type, full, meta) {
                            var price = '0.00';
                            if (full.price != '' && full.price != null) {
                                price = full.price;
                            }
                            return 'AED '+price;
                        }
                    },
                    {
                        data: "users",
                        visible: true
                    },
                    {
                        data: "menus",
                        visible: true
                    },
                    {
                        data: "categories",
                        visible: true
                    },
                    {
                        data: "items",
                        visible: true
                    },
                    {
                        data: "devices_limit",
                        visible: true
                    },
                    {
                        data: "duration",
                        visible: true
                    },
                    {
                        data: "is_deleted",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            
                            var action = '';
                            var package_id;
                            var disabled = '';
                            var btnname = 'Upgrade';
                            $.each(JSON.parse(package), function(key,val) {
                                // console.log(package_id);
                                // console.log(full);
                                // console.log(val);

                                if(val['id'] == full.pid)
                            {
                                package_id = full.pid; 
                            }
                            
                             if(val['id'] == full.id)
                            {
                                  package_id = full.id;
                            }

                            if(val.pid == full.id){
                               btnname='Renew';
                               disabled='';
                            }

                            }
                            );

                              if(package_id == full.id)
                            {
                                disabled = 'disabled="disabled"'
                            }
                             
                            var send_request = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                action +='<a href="'+ send_request +'restaurant/packages/send_request/'+ btoa(full.id) + '" data_id="'+full.name+'" '+ disabled +' class="btn btn-default" onclick="return confirm_alert(this)">'+btnname+'</a>'
                                action +='</div>'
                            return action;
                        }
                    }
                ]
            });
        });
    });


    function confirm_alert(e) {
        swal({
            html: "You're about to request an upgrade/subscription to the <b>" + $(e).attr('data_id') + "</b>, After confirming, someone from our sales team will get back to you.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Send!",
            cancelButtonText: "No, cancel plz!"
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
        });
        return false;
    }


    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("restaurant/packages/view_package"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#packagebody').html(response);
                $('#myModal').modal('show');
            }
        });
    });

</script>