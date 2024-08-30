<style type="text/css">
    a[disabled="disabled"] {
        pointer-events: none;
    }
</style>
<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Package Request</li>
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
                        <h4 class="modal-title">View Package Request</h4>
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
            <table id="package_request" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Rest Name</th>
                        <th>Email</th>
                        <th>Package Name</th>
                        <th>Status</th>
                        <th>Requested Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
//var users;
var items,categories,menus,devices_limit;
    $(document).ready(function() {
        $(function () {
            $('#package_request').dataTable({
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
                ajax: '<?php echo base_url("admin/packagerequest/get_package_request"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "user_name",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "restaurant_name",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "user_email",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "package_name",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "status",
                        visible: true,
                        render: function (data, type, full, meta) {
                            var status;
                            var data = full.status.charAt(0).toUpperCase() + full.status.slice(1);
                            //let  date = new Date();
                            // console.log(date.getDate());
                            //let reqdate=date.setDate(full.request_date + 30);
 
                            if(full.status == 'new')
                            {
                                status = '<span class="label label-info">'+ data +'</span>';
                            }else if(full.status == 'activate')
                            {
                                status =  '<span class="label label-default">'+data+'</span>';
                            }else if(full.status == 'pendding')
                            {
                                status =  '<span class="label label-warning">'+data+'</span>';
                            }else
                            {
                                status =  '<span class="label label-danger">'+data+'</span>';
                            }

                             console.log(full);
                            let today = new Date().toISOString().slice(0, 10);
                             
                            if(today>full.end_date && full.package_id!=3 && full.status == 'activate')
                            {
                                status =  '<span class="label label-danger">'+'Deactivate'+'</span>';
                                 console.log('Deactivatesss');
                            }else{

                            var newdate = new Date(full.request_date);
                            let reqdate1=newdate.setDate(newdate.getDate() + 30);
                            //console.log(newdate);

                             var reqdate = new Date(reqdate1).toISOString().slice(0, 10);
                            console.log(reqdate);
                            } 


                            if(today>reqdate  && full.package_id==3 && full.status == 'activate'){
                                 status =  '<span class="label label-danger">'+'Deactivate'+'</span>';
                                console.log('Deactivatesss'); 
                            }



                            return status;
                        }
                    },
                    {
                        data: "created_at",
                        visible: true,
                    },
                    {
                        data: "is_deleted",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            //users = full.items;
                            var action = '';
                            var disable;
                            var assign_package = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                if(full.status === 'activate')
                                {
                                    disable = 'disabled="disabled"';
                                }
                                action +='<a class="icons-design" href="'+ assign_package +'admin/packagerequest/assign_package/'+ btoa(full.id) + '" '+ disable +' data-pkg_id="' + full.pkg_id + '" data-users="' + full.users + '" data-menus="' + full.menus + '" data-categories="' + full.categories + '" data-items="' + full.items + '" data-devices_limit="' + full.devices_limit + '" onclick="return confirm_alert(this)"><img src="<?php echo base_url('public/assign.svg'); ?>"></a>'
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                if(full.status === 'activate')
                                {
                                    disable = 'disabled="disabled"';
                                }
                                action +='<li><a href="'+ assign_package +'admin/packagerequest/assign_package/'+ btoa(full.id) + '" '+ disable +' data-pkg_id="' + full.pkg_id + '" data-users="' + full.users + '" data-menus="' + full.menus + '" data-categories="' + full.categories + '" data-items="' + full.items + '" data-devices_limit="' + full.devices_limit + '" onclick="return confirm_alert(this)">Assign Package</a></li>'
                                // action +='<li><a href="javascript:void(0)" onclick="return linkThing(this)" data-id="' + btoa(full.id) + '" title="Change Status">Change Status</a></li>'
                                action +='</ul>'*/
                                action +='</div>'
                            return action;
                        }
                    }
                ]
            });
        });
    });
// <tr><td><a href='admin/packages/edit/"+ btoa($(e).attr('data-pkg_id')) +"'>Edit Package<a></td></tr>
   function confirm_alert(e) {
        swal({
            html: "<center><table style='font-size:14px;'><tr><td><b>Users Limit</b> </td><td>" + $(e).attr('data-users') +"</td></tr><tr><td><b>Menus Limit</b> </td><td>"+ $(e).attr('data-menus') +"</td></tr><tr><td><b>Categories Limit</b> </td><td>"+ $(e).attr('data-categories') +"</td><tr><td><b>Items Limit</b> </td><td>"+ $(e).attr('data-items') +"</td></tr><tr><td><b>Devices Limit</b>  </td><td>"+ $(e).attr('data-devices_limit') +"</td></tr></table><center><br/> Are you sure?",
            text: "Assign Package!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
        }).catch(swal.noop);
        return false;
    }

 
    function linkThing(e) 
    {
        var inputOptionsPromise = new Promise(function (resolve) {
            setTimeout(function () {
                $.getJSON("<?php echo base_url('admin/packagerequest/get_status') ?>", function (data) {
                    resolve(data);
                });
            }, 100)
        })

        swal({
            title: 'Select Status',
            input: 'select',
            inputOptions: inputOptionsPromise,
            inputPlaceholder: 'Select Status',
            showCancelButton: true,
            inputValidator: function (value) {
                return new Promise(function (resolve, reject) {
                    if (value != '') 
                    {
                        $.ajax({
                            url: '<?php echo base_url('admin/packagerequest/change_status'); ?>',
                            type: "POST",
                            data: {'status': value, 'request_id': $(e).attr('data-id')},
                            dataType: "json",
                            success: function (data) {
                                console.log(data.success);
                                if (data.success) {
                                    location.reload();
                                    // resolve();
                                } else {
                                    reject('something went wrong please try again.');
                                }
                            }
                        });
                    } else {
                        reject('You need to select one tag.');
                    }
                })
            }
        }).then(function (result) {
            window.location.href = $(e).attr('href');
        }).catch(swal.noop);
    }
</script>