<?php
    if(is_sub_admin())
    {
        $restaurant_id = 22334455;
    }
    else
    {
        $restaurant_id = 22334455;
    }
?>

<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li class="active">Menus</li>
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
                        <h4 class="modal-title">View Menus</h4>
                    </div>
                    <div id="menubody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->

    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="menu_enable" style="display:none">
        <div id="m_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="menu_disable" style="display:none">
        <div id="m_disable"></div>
    </div>

    <div class="panel panel-default">
        <div style="text-align: right; margin-bottom: 10px;">
        <?php if ($menu_limit < $this->session->userdata('login_user')['menus_limit'])  {?>
           <!--  <a href="<?php echo base_url('restaurant/menus_default/add'); ?>" class="btn btn-default">Add New</a> -->
        <?php }?>
        </div>
        <div class="panel-body table-responsive">
            <table id="menus" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Background Image</th>
                        <th>Title</th>
                        <th>Arabic</th>
                        <th>Feedback</th>
                        <th>Enable/Disable</th>
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
        var menus = '<?php echo json_encode($menus); ?>';
        var restaurantid = <?php echo 22334455;//$this->session->userdata('login_user')['id'];?>;
        $(function () {
            $('#menus').dataTable({
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
                ajax: '<?php echo base_url("restaurant/menus_default/get_menus"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "background_image",
                        visible: true,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var img = '';
                            if (data != null) {
                                img = '<a class="fancybox" href="<?php echo base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/menus/backgrounds/'); ?>'+data+'"" data-fancybox-group="gallery" ><img src="<?php echo base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/menus/backgrounds/'); ?>'+data+'" height="55px" width="55px" alt="' + full.name + '" class="img-circle"/></a>';
                            } else {
                                img = '<a class="fancybox" href="<?php echo base_url(DEFAULT_IMG)?>" data-fancybox-group="gallery" ><img src="<?php echo base_url(DEFAULT_IMG) ?>" height="55px" width="55px" alt="' + full.name + '" class="img-circle"/></a>';
                            }
                            return img;
                        }
                    },
                    {
                        data: "title",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "arabian_title",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "is_disable_feedback",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var checked_box = ''
                            if (full.is_disable_feedback == 1) {
                                checked_box = 'checked="checked"';
                            }
                            var status =  '<div class="checkbox margin-t-0" style="text-align: center;"><input id="feedback'+ full.id +'" class="feedback" data-id="'+ full.id +'" type="checkbox"' + checked_box + ' ><label for="feedback'+ full.id +'"></label></div>';
                            return status;
                        }
                    },
                    {
                        data: "is_active",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var checked_box = '';
                            var disabled = '';
                            
                            if (full.is_active == 1) {
                                checked_box = 'checked="checked"';
                            }
                            var status =  '<div class="checkbox margin-t-0" style="text-align: center;"><input id="checkbox'+ full.id +'" class="chkbox" data-id="'+ full.id +'" type="checkbox"' + checked_box + ' '+ disabled +'><label for="checkbox'+ full.id +'"></label></div>';
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
                            var menu_id;
                           /* console.log( ' full='+full);
                            console.log( ' menus='+menus);*/
                            $.each(JSON.parse(menus), function(key,val) {
                            if(val['id'] == full.id)
                            {
                                menu_id = full.id;
                            } 
                            });
                            var deleteurl = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                action +='<a class="icons-design" href="restaurant/menus_default/edit/' + btoa(full.id) + '"><img src="https://www.cherrymenu.com/login/public/edit-change-pencil.svg"></a>&nbsp;'
                                
                                action +='<a href="javascript:void(0)" class="view_btn icons-design" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '><img src="https://www.cherrymenu.com/login/public/Eye.svg"></a>&nbsp;'
                               /* if(menu_id != full.id)
                                {
                                action +='<a class="icons-design" href="'+ deleteurl +'restaurant/menus_default/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="https://www.cherrymenu.com/login/public/Trashcan.svg"></a>&nbsp;'
                                }*/
                                /*action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="restaurant/menus/edit/' + btoa(full.id) + '">Edit</a></li>'
                                if(menu_id != full.id)
                                {
                                    action +='<li><a href="'+ deleteurl +'restaurant/menus/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                }
                                action +='<li><a href="javascript:void(0)" class="view_btn" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '>View</a></li>'
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
            text: "You will not be able to recover this menu!",
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
                swal("Cancelled", "Your menu is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("restaurant/menus/view_menu"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#menubody').html(response);
                $('#myModal').modal('show');
            }
        });
    });

    $(document).on('click', '.chkbox', function () {
        $('#menu_disable').hide();
        $('#menu_enable').hide();
        var menu_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/menus/change_status"); ?>',
            type: "POST",
            data: {id: menu_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#m_disable').html(data.msg);
                    $('#menu_disable').show();
                    setTimeout(function(){ $('#menu_disable').hide() }, 3000);
                    $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                   // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }else{
                    $('#m_enable').html(data.msg);
                    $('#menu_enable').show();
                    setTimeout(function(){ $('#menu_enable').hide() }, 3000);
                    $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                    // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }
            }
        });
    });

    $(document).on('click', '.feedback', function () {
        $('#menu_disable').hide();
        $('#menu_enable').hide();
        var menu_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/menus/change_feedback"); ?>',
            type: "POST",
            data: {id: menu_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#m_disable').html(data.msg);
                    $('#menu_disable').show();
                    setTimeout(function(){ $('#menu_disable').hide() }, 3000);
                     $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                   // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }else{
                    $('#m_enable').html(data.msg);
                    $('#menu_enable').show();
                    setTimeout(function(){ $('#menu_enable').hide() }, 3000);
                    $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                    // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }
            }
        });
    });
</script>
<?php if($this->session->userdata('login_user')['id'] !="" && !empty($this->session->userdata('login_user')['id'])){
    $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
  foreach ($query->result() as $row) {?>
     <?php $latesttimestamp = $row->latesttimestamp;?>
<?php }
}
}
?>
<script src="https://www.gstatic.com/firebasejs/5.8.1/firebase.js"></script>
<input type="hidden" name="latesttimestamp" id="latesttimestamp" value="<?php echo $latesttimestamp; ?>">
<script type="text/javascript">
  //  $( document ).ready(function() {
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
           // alert(latesttimestamp);
            firebase.database().ref('Restaurants/' + restaurantid).set({
                LastUpdatedTime: latesttimestamp,
            }); 
                 
        }
       // });

    </script>