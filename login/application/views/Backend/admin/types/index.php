<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Types</li>
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

    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="helptopic_enable" style="display:none">
        <div id="rest_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="helptopic_disable" style="display:none">
        <div id="rest_disable"></div>
    </div>

    <div class="panel panel-default">
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="<?php echo base_url('admin/types/add'); ?>" class="btn btn-default">Add New</a>
        </div>
        <div class="panel-body table-responsive">
            <table id="helptopics" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th style="text-align:center">Enable/Disable</th>
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
            $('#helptopics').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                order: [[3, "desc"]],
                ajax: '<?php echo base_url("admin/types/get_types"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "type",
                        visible: true,
                        render: $.fn.dataTable.render.text()
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
                            var status =  '<div class="checkbox margin-t-0" style="text-align:center"><input id="checkbox'+ full.id +'" class="chkbox" data-id="'+ full.id +'" type="checkbox"' + checked_box + '><label for="checkbox'+ full.id +'"></label></div>';
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
                            var action = '';
                            var deleteurl = '<?php echo base_url(); ?>';
                            // if (full.is_active == 1) {
                                action +='<div class="btn-group">'
                                action +='<a class="icons-design" href="admin/types/edit/' + btoa(full.id) + '"><img src="https://www.cherrymenu.com/login/public/edit-change-pencil.svg"></a>&nbsp;'
                                
                                action +='<a class="icons-design" href="'+ deleteurl +'admin/types/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="https://www.cherrymenu.com/login/public/Trashcan.svg"></a>'
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="admin/types/edit/' + btoa(full.id) + '">Edit</a></li>'
                                action +='<li><a href="'+ deleteurl +'admin/types/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
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
            text: "You will not be able to recover this type!",
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
                swal("Cancelled", "Your type is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.chkbox', function () {
        $('#helptopic_disable').hide();
        $('#helptopic_enable').hide();
        var topic_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("admin/types/change_status"); ?>',
            type: "POST",
            data: {id: topic_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#helptopic_disable').show();
                    setTimeout(function(){ $('#helptopic_disable').hide() }, 3000);
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#helptopic_enable').show();
                    setTimeout(function(){ $('#helptopic_enable').hide() }, 3000);
                }
            }
        });
    });
</script>