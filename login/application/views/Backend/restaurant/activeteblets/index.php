<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li class="active">Devices</li>
    </ol>
</div>
<?php 
$a = file_get_contents('https://itunes.apple.com/lookup?id=1441509800');
$b = json_decode($a);
$c = $b->results[0]->version;
?>
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
<input type="hidden" id="versions" value="<?= $c ?>">
<input type="hidden" id="androidversions" value="<?= $androidversion['android_version'];
 ?>">
    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="device_enable" style="display:none">
        <div id="dev_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="device_disable" style="display:none">
        <div id="dev_disable"></div>
    </div>
    <div class="panel panel-default">
        <div style="text-align: right; margin-bottom: 10px;">     
        </div>
        <div class="panel-body table-responsive">
            <table id="devices" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Version</th>
                        <th>App Version</th>
                        <th>Device</th>
                        <th>Is Login</th>
                        <th>Created On</th>
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
                ajax: '<?php echo base_url("restaurant/activetablets/get_devices"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "name",
                        visible: true,
                        searchable: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "version",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "app_version",
                        visible: true,
                       // render: $.fn.dataTable.render.text()
                        render: function (data, type, full, meta) {
                            var checked_box = '';
                            var ver = $('#versions').val();
                            var andver = $('#androidversions').val(); 
                           // console.log(full.device);
                            if(full.device == 'IOS'){
                              if (full.app_version == ver) {
                                return full.app_version;
                            }else
                            {
                                return '<b style="color:red;">'+full.app_version+'<span style="color:black;">&nbsp;(Need to Update)</span></b>';
                            }  
                        }else{
                           if(full.app_version == andver){
                                return full.app_version;
                           }else{
                                return '<b style="color:red;">'+full.app_version+'<span style="color:black;">&nbsp;(Need to Update)</span></b>';
                           }
                        }
                            
                            
                        }
                    },
                    {
                        data: "device",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "is_login",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var checked_box = '';
                            if (full.is_login == 1) {
                                return 'Yes';
                            }else
                            {
                                return 'No';
                            }
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
                            var action = '';
                            var deleteurl = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                action +='<a class="icons-design" href="'+ deleteurl +'restaurant/activetablets/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="<?php echo base_url('public/Trashcan.svg'); ?>"></a>'
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="'+ deleteurl +'restaurant/activetablets/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                action +='</ul>'*/
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
            title: "Are you sure?",
            text: "You will not be able to recover this record!",
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
                swal("Cancelled", "Your record is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.chkbox', function () {
        $('#device_disable').hide();
        $('#device_enable').hide();
        var device_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/activetablets/change_status"); ?>',
            type: "POST",
            data: {id: device_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#dev_disable').html(data.msg);
                    $('#device_disable').show();
                    setTimeout(function(){ $('#device_disable').hide() }, 3000);
                }else{
                    $('#dev_enable').html(data.msg);
                    $('#device_enable').show();
                    setTimeout(function(){ $('#device_enable').hide() }, 3000);
                }
            }
        });
    });
</script>