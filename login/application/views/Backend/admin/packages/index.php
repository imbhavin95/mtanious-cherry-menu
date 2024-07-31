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
        <div style="text-align: right; margin-bottom: 10px;">
            <div class="btn-group">
                <button type="button" class="btn btn-default" data-toggle="dropdown" aria-expanded="false">Add New</button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="package">
                    <li><a href="<?php echo base_url('admin/packages/freePackage'); ?>">Free Package</a></li>
                    <li><a href="<?php echo base_url('admin/packages/add'); ?>">Paid Package</a></li>
                </ul>
            </div>
            <!-- <div class="form-group">
                <label class="col-sm-4 control-label form-label">Add New</label>
                <div class="col-sm-8">
                <select class="selectpicker">
                    <option>Free Package</option>
                    <option>Paid Package</option>
                    </select>                  
                </div>
            </div> -->
            <!-- <a href="<?php echo base_url('admin/packages/add'); ?>" class="btn btn-default">Add New</a> -->
        </div>
        <div class="panel-body table-responsive">
            <table id="packages" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th style="text-align:center">Enable/Disable</th>
                        <th style="width: 60px;">Period</th>
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
                order: [[6, "desc"]],
                ajax: '<?php echo base_url("admin/packages/get_packages"); ?>',
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
                        data: "type",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "description",
                        visible: true,
                        render: function ( data, type, row ) {
                            return data.length > 50 ?
                                data.substr( 0, 50 ) +'…' :
                                data;
                        }
                    },
                    {
                        data: "is_active",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var checked_box = '';
                            if (full.is_active == 1) {
                                checked_box = 'checked="checked"';
                            }
                            var status =  '<div class="checkbox margin-t-0" style="text-align: center;"><input id="checkbox'+ full.id +'" class="chkbox" data-id="'+ full.id +'" type="checkbox"' + checked_box + '><label for="checkbox'+ full.id +'"></label></div>';
                            return status;
                        }
                    },
                    {
                        data: "duration",
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
                            // if (full.is_active == 1) {
                                action +='<div class="btn-group">'
                                action +='<a class="icons-design" href="admin/packages/edit/' + btoa(full.id) + '"><img src="https://www.cherrymenu.com/login/public/edit-change-pencil.svg"></a>&nbsp;'
                                action +='<a href="javascript:void(0)" class="view_btn icons-design" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '><img src="https://www.cherrymenu.com/login/public/Eye.svg"></a>&nbsp;'
                                action +='<a class="icons-design" href="'+ deleteurl +'admin/packages/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="https://www.cherrymenu.com/login/public/Trashcan.svg"></a>&nbsp;'
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="admin/packages/edit/' + btoa(full.id) + '">Edit</a></li>'
                                action +='<li><a href="'+ deleteurl +'admin/packages/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                action +='<li><a href="javascript:void(0)" class="view_btn" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '>View</a></li>'
                                // action +='<li><a href="admin/packages/assignpackage/' + btoa(full.id) + '">Assign Package</a></li>'
                                action +='</ul>'*/
                                action +='</div>'
                            // }
                            return action;
                        }
                    }
                ]
            });
        });
    });


    function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this package!",
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
                swal("Cancelled", "Your package is safe :)", "error");
            }
        });
        return false;
    }


    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("admin/packages/view_package"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#packagebody').html(response);
                $('#myModal').modal('show');
            }
        });
    });


    $(document).on('click', '.chkbox', function () {
        $('#package_disable').hide();
        $('#package_enable').hide();
        var package_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("admin/packages/change_status"); ?>',
            type: "POST",
            data: {id: package_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#package_disable').show();
                    setTimeout(function(){ $('#package_disable').hide() }, 3000);
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#package_enable').show();
                    setTimeout(function(){ $('#package_enable').hide() }, 3000);
                }
            }
        });
    });
</script>