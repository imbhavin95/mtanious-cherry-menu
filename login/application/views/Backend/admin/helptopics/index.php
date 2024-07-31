<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Help Topics</li>
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
                        <h4 class="modal-title">View Help Topic</h4>
                    </div>
                    <div id="helptopicbody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" style="padding-top: 68px; width: 383px;">
            <div class="modal-content">
                <div id="changePasswordbody">
                    <div class="login-form" style="padding-top: 100px;">
                        <form action="" id="chnpswdform" method="post">
                            <div class="top">
                                <h1>Change Password</h1>
                            </div>
                            <div class="form-area">
                                <div class="group">
                                    <input type="text" id="email" name="email" class="form-control" data-id="" placeholder="E-mail">
                                    <input type="hidden" id="hidden_id" name="hidden_id" value=""/>
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <div class="group">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                    <i class="fa fa-key"></i>
                                </div>
                                <div class="group">
                                    <input type="password" name="con_password" id="con_password" class="form-control" placeholder="Confirm Password">
                                    <i class="fa fa-key"></i>
                                </div>
                                <div class="group">
                                    <button type="submit" class="btn btn-default">CHANGE PASSWORD</button>
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->

    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="helptopic_enable" style="display:none">
        <div id="rest_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="helptopic_disable" style="display:none">
        <div id="rest_disable"></div>
    </div>

    <div class="panel panel-default">
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="<?php echo base_url('admin/Helptopics/add'); ?>" class="btn btn-default">Add New</a>
        </div>
        <div class="panel-body table-responsive">
            <table id="helptopics" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
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
                order: [[4, "desc"]],
                ajax: '<?php echo base_url("admin/helptopics/get_topics"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "title",
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
                                action +='<a class="icons-design" href="admin/helptopics/edit/' + btoa(full.id) + '"><img src="https://www.cherrymenu.com/login/public/edit-change-pencil.svg"></a>&nbsp;'
                                action +='<a href="javascript:void(0)" class="view_btn icons-design" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '><img src="https://www.cherrymenu.com/login/public/Eye.svg"></a>&nbsp;'
                                action +='<a class="icons-design" href="'+ deleteurl +'admin/helptopics/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="https://www.cherrymenu.com/login/public/Trashcan.svg"></a>&nbsp;'
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="admin/helptopics/edit/' + btoa(full.id) + '">Edit</a></li>'
                                action +='<li><a href="'+ deleteurl +'admin/helptopics/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                action +='<li><a href="javascript:void(0)" class="view_btn" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '>View</a></li>'
                                action +='</ul>'*/
                                action +='</div>'
                            // }
                            return action;
                        }
                    }
                ]
            });
        });

        $('#chnpswdform').validate(
        {
            rules:
            {
                email :{required:true,email:true,
                    remote: {
                        url: '<?php echo base_url('admin/restaurant/checkUniqueEmail'); ?>',
                        type: "post",
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            restaurant_id: function () {
                                return btoa($("#email").attr('data-id'));
                            },
                        },
                    }
                    },
                password: {
                        required: true,
                        minlength: 5
                    },
                    con_password: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
            },
            messages:
            {
                email:{required:"This field is required",remote: "The email is already used."},
                password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 5 characters"
                    },
                    con_password:
                    {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 5 characters",
                        equalTo: "Please enter the same password as above"
                    },
            },
            submitHandler: function (form)
            {
                $('button[type="submit"]').attr('disabled', true);
                var email = $('#email').val();
                var password = $('#password').val();
                var id = $('#email').attr('data-id');
                $.ajax({
                    url: '<?php echo base_url("admin/restaurant/update_password"); ?>',
                    type: "POST",
                    data: {email: email,password : password,id : id},
                    success: function (data) {
                        
                    }
                });
               // form.submit();
            },
        });
    });

    function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this help topic!",
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
                swal("Cancelled", "Your help topic is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("admin/helptopics/view_helptopic"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#helptopicbody').html(response);
                $('#myModal').modal('show');
            }
        });
    });


    $(document).on('click', '.chkbox', function () {
        $('#helptopic_disable').hide();
        $('#helptopic_enable').hide();
        var topic_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("admin/helptopics/change_status"); ?>',
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