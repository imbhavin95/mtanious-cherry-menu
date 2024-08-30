<style>
    .dt-button {margin-left: 11px;width: 60px;height: 35px;background: #26A69A;padding: 6px;text-align: center;border-radius: 5px;color: white;font-weight: bold;border: 0px solid #fff;}
    .dt-buttons a:hover,.dt-buttons a:focus {color: #26A69A !important;background: #fff !important;border: 2px solid #26A69A;text-decoration:none;}
    .custom_perpage_dropdown .dataTables_length {margin: 0 18px 20px 20px;}
    .dataTables_info {padding: 8px 22px;margin-bottom: 10px;}
    .dataTables_paginate {margin: 10px 20px 20px 20px;}
</style>

<?php
    if(is_sub_admin())
    {
        $restaurant_id = $this->session->userdata('login_user')['restaurant_id'];
    }
    else
    {
        $restaurant_id = $this->session->userdata('login_user')['id'];
    }
//print_r($itemlist);
//echo $restaurant_id;
/*if(!empty($itemlist)){
    foreach ($itemlist as $r) {
   $sqlv = "select * from item_images where is_active ='1' and is_deleted ='0' and item_id = '".$r['id']."' and convert_status = '0'";//
   $queryv = $this->db->query($sqlv);
    if ($queryv->num_rows() > 0) {
  foreach ($queryv->result() as $rowv) { 
            $allowed =  array('avi','mp4','flv','wmv');
            $ext = pathinfo($rowv->image, PATHINFO_EXTENSION);
            if(in_array($ext,$allowed) ) {
                $v = '/var/www/html/login/public/restaurants/171/items/'.$rowv->item_id.'/'.$rowv->image;
               $v1 = '/var/www/html/login/public/restaurants/171/items/'.$rowv->item_id.'/'.$rowv->image;
              $info = pathinfo($rowv->image);
             //print_r($info['filename']); 
             //echo $info['filename'];
             //die;
            $updatetime = uniqid() . time(). '.' . $ext;
            $v2 = '/var/www/html/login/public/restaurants/171/items/'.$rowv->item_id.'/'.$updatetime;
            $sqlv1 = "UPDATE `item_images` SET `is_deleted`= '1', `convert_status` = '1' WHERE id = '".$rowv->id."'";
            $queryv1 = $this->db->query($sqlv1);
            $sqlv2 = "INSERT INTO `item_images`(`item_id`, `image`, `is_active`, `is_deleted`,`convert_status`) VALUES ('".$rowv->item_id."','".$updatetime."','1','0','1')";
            $queryv2 = $this->db->query($sqlv2);  
          //  $ret = exec("ffmpeg -i ".$v." -b:v 1300k -preset ultrafast ".$v2, $out, $err);
        $ret = exec("ffmpeg -i ".$v." -vcodec libx264 -crf 23 ".$v2, $out, $err);
      // $ret = exec("ffmpeg -i ".$v." -b:v 2048k -s 1280x720 -fs 2048k -vcodec mpeg4 -acodec copy".$v2, $out, $err);
               //$ret = exec("ffmpeg -y -loglevel error -i ".$v." -vcodec libx264 -crf 28 -preset faster -tune film ".$v,$out, $err);
               // exec("/var/www/html/ffmpeg/code/ffmpeg.exe");
                //exec("ffmpeg -i ".$v." -c:v libx264 -preset slow -crf 22 -s 1280x720 -c:a libfaac -b:a 128K ".$v2);
              // $a = exec("ffmpeg -err_detect ignore_err -i ".$v." -vcodec libx264 -crf 28 -preset faster -tune film ".$v2);
           //  var_dump($ret);
            }
     } 
    }
 } 
}*/

?>

<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li class="active">Items</li>
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
                        <h4 class="modal-title">View Items</h4>
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
        <div style="text-align: right; margin-bottom: 10px;">
        <select class="selectpicker  col-md-2" placeholder="Select 1 or more options"  id="menus" name="menus[]" multiple>
        <!-- <option value="" selected disabled>Select Menus</option> -->
            <?php 
                if(!empty($menus))
                {
                    foreach($menus as $row) 
                    { ?>
                        <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['title']); ?></option>
                    <?php 
                    } 
                }
            ?>
        </select>
        <select class="selectpicker col-md-2" id="categories" name="categories[]" multiple>
            <!-- <?php 
                if(!empty($categories))
                {
                    foreach($categories as $row) 
                    { 
                        //print_r($row);
                        ?>
                        <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['title']); ?></option>
                    <?php 
                    } 
                }
            ?> -->
        </select>
       <button class="btn btn-default" id="hide_items">Hide disabled items</button>
        <?php if ($item_limit <  $this->session->userdata('login_user')['items_limit']) { ?>
            <a href="<?php echo base_url('restaurant/items/add'); ?>" class="btn btn-default">Add New</a>
        <?php } ?>
        </div>
        <div class="panel-body table-responsive">
            <table id="items" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Arabic</th>
                        <th>Price</th>
                        <th style="text-align:center">Is Feature</th>
                        <th style="text-align:center">Enable/Disable</th>
                        <th>Action</th>
                        <th>Calories</th>
                        <th>Time</th>
                        <th>Is Active</th>
                        <th>Is Featured</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div> 
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script>
    var restaurantid = <?php echo $this->session->userdata('login_user')['id'];?>;
    var is_active = 0;
    var category_array;
    var menus_array;
    function bind() {
        $('#items').dataTable({
            autoWidth: false,
                    processing: true,
                    serverSide: true,
                    bStateSave: true,
                    language: {
                        search: '<span>Search:</span> _INPUT_',
                        lengthMenu: '<span>Show:</span> _MENU_',
                        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                    },
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    order: [[0, "desc"]],
            dom: 'lBfrtipx',
            buttons: [
                {
                    extend: 'excel',
                    exportOptions: 
                    {
                        columns: [ 0,1,2,3,7,8,9,10]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: 
                    {
                        columns: [0,1,3,7,8,9,10],
                    },
                    customize : function(doc) 
                    {
                        doc.styles.tableHeader.alignment = 'left';
                        doc.content[1].table.widths = [ '5%', '20%', '15%', '15%', '20%', '10%','15%'];
                    }
                }
            ],
            // ajax: 'admin/users/list_user',
            ajax: {
                'url': '<?php echo base_url("restaurant/items/get_items"); ?>',
                "data": {
                    'is_active': is_active,
                    'category_array' : category_array,
                    'menus_array' : menus_array
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
                            data: "price",
                            visible: true,
                            render: function (data, type, full, meta) {
                                var price = '0.00';
                                if (full.price != '' && full.price != null) {
                                    price = full.price;
                                }
                                return "<?php echo $currency_code;?> "+parseFloat(price).toFixed(2);//price;
 
                            }
                        },
                        {
                            data: "is_featured",
                            visible: true,
                            searchable: false,
                            sortable: false,
                            render: function (data, type, full, meta) {
                                var checked_box = '';
                                if (full.is_featured == 1) {
                                    checked_box = 'checked="checked"';
                                }
                                var status =  '<div class="checkbox margin-t-0" style="text-align: center;"><input id="checkbx'+ full.id +'" class="isfeature" get-data="'+ full.id +'" type="checkbox"' + checked_box + '><label for="checkbx'+ full.id +'"></label></div>';
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
                                if (full.status == 1) {
                                    checked_box = 'checked="checked"';
                                }
                                var status =  '<div class="checkbox margin-t-0" style="text-align: center;"><input id="checkbox'+ full.id +'" class="isactive" data-id="'+ full.id +'" type="checkbox"' + checked_box + '><label for="checkbox'+ full.id +'"></label></div>';
                                return status;
                            }
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
                                    action +='<div class="btn-group order_row1" data-id="' + full.id + '">'
                                    action +='<a class="icons-design" href="restaurant/items/edit/' + btoa(full.id) + '"><img src="<?php echo base_url('public/edit-change-pencil.svg'); ?>"></a>&nbsp;'
                                    action +='<a href="javascript:void(0)" class="view_btn icons-design" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '><img src="<?php echo base_url('public/Eye.svg'); ?>"></a>&nbsp;'
                                    action +='<a class="icons-design" href="'+ deleteurl +'restaurant/items/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)"><img src="<?php echo base_url('public/Trashcan.svg'); ?>"></a>&nbsp;'
                                   /* action +='<button type="button" class="btn btn-light">Action</button>'
                                    action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                    action +='<span class="caret"></span>'
                                    action +='<span class="sr-only">Toggle Dropdown</span>'
                                    action +='</button>'
                                    action +='<ul class="dropdown-menu" role="menu">'
                                    action +='<li><a href="restaurant/items/edit/' + btoa(full.id) + '">Edit</a></li>'
                                    action +='<li><a href="'+ deleteurl +'restaurant/items/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                    action +='<li><a href="javascript:void(0)" class="view_btn" id="' + btoa(full.id) + '" data-id=' + btoa(full.id) + '>View</a></li>'
                                    // action +='<li><a href="restaurant/ItemImages/index/' + btoa(full.id) + '">Images / Videos</a></li>'
                                    action +='</ul>'*/
                                    action +='</div>'
                                // }
                                return action;
                            }
                        },
                        {
                            data: "calories",
                            visible: false
                        },
                        {
                            data: "time",
                            visible: false
                        },
                        {
                            data: "is_active",
                            visible: false,
                            render: function (data, type, full, meta) {
                                if (full.is_active == 1) {
                                    return 'Yes';
                                }else{
                                    return 'No';
                                }
                            }
                        },
                        {
                            data: "is_featured",
                            visible: false,
                            render: function (data, type, full, meta) {
                                if (full.is_featured == 1) {
                                    return 'Yes';
                                }else{
                                    return 'No';
                                }
                            }
                        }
                    ]
        });
    }

    //Get All categories
    function getAllCategories()
    {
        $.ajax({
                url:  '<?php echo base_url() ?>restaurant/items/getAllCategories',
                type: "GET",
                datatype : "json",
                success:function(data) 
                {
                    $('#categories').empty();
                    var data = JSON.parse(data);
                    $.each(data, function(key, value) {
                        $('#categories').append('<option value="'+ value.id +'">'+ value.title +'</option>');
                        $('.selectpicker').selectpicker('refresh');
                    });
                }
            });
    }

    $(document).ready(function() {
        $('#menus').selectpicker({noneSelectedText: 'Select Menus'});
        $('#categories').selectpicker({noneSelectedText: 'Select Categories'});
        bind();
        getAllCategories();

        //For rearrange
        $( "#items" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });
    });

    $('#hide_items').on('click', function () {
        if(is_active == 1){
            $('#hide_items').html('Hide disabled items');
            is_active = 0;
        }else {
            is_active = 1;
            $('#hide_items').html('Show disabled items');
        }
        $("#items").dataTable().fnDestroy();
        bind();
    });

    $('#categories').on('change', function () {
        category_array = $(this).val();
        $("#items").dataTable().fnDestroy();
        bind();
    });

    $('#menus').on('change', function () {
        menus_array = $(this).val();
        $("#items").dataTable().fnDestroy();
        bind_categories(menus_array); 
        bind();
    
    });

    function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this item!",
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
                swal("Cancelled", "Your item is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.view_btn', function () {
        $.ajax({
            url: '<?php echo base_url("restaurant/items/view_item"); ?>',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#restaurantbody').html(response);
                $('#myModal').modal('show');
            }
        });
    });

    $(document).on('change', '.isactive', function () {
        //console.log('active');
        $('#restaurant_disable').hide();
        $('#restaurant_enable').hide();
        var item_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/items/change_status"); ?>',
            type: "POST",
            data: {id: item_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#restaurant_disable').show();
                    $("#items").dataTable().fnDestroy();
                    // var atext=$("a.paginate_button.current").attr("data-dt-idx");
                    // //alert(atext);$('a.paginate_button').attr('data-dt-idx',2).click()
                    // console.log(atext);
                    bind();
                    setTimeout(function(){;$('#restaurant_disable').hide(); }, 2000);
                    // setTimeout(function(){   }, 3000);
                    $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                   // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#restaurant_enable').show();
                    $("#items").dataTable().fnDestroy();
                    // var atext=$("a.paginate_button.current").attr("data-dt-idx");
                    // console.log(atext);//alert(atext);$('a.paginate_button').attr('data-dt-idx',2).click();
                    bind();
                    setTimeout(function(){$('#restaurant_enable').hide(); }, 2000);
                    // setTimeout(function(){ $('#restaurant_enable').hide();  }, 3000);
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

    $(document).on('change', '.isfeature', function () {
        //console.log('feature');
        $('#restaurant_disable').hide();
        $('#restaurant_enable').hide();
        var item_id = $(this).attr('get-data');
        $.ajax({
            url: '<?php echo base_url("restaurant/items/is_feature"); ?>',
            type: "POST",
            data: {id: item_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#restaurant_disable').show();
                    $("#items").dataTable().fnDestroy();
                    bind();
                    setTimeout(function(){ $('#restaurant_disable').hide() }, 3000);
                    $("#latesttimestamp").attr('value', data.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                   // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#restaurant_enable').show();
                    $("#items").dataTable().fnDestroy();
                    bind();
                    setTimeout(function(){ $('#restaurant_enable').hide() }, 3000);
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

    function bind_categories(menuID)
    {
        //console.log(menuID);
        if(menuID == null)
        {
            getAllCategories();
        }else
        {
            var post_url = '<?php echo base_url() ?>restaurant/items/getCategories'
                $.ajax({
                    url: post_url,
                    type: "POST",
                    data : { "menuId" : menuID },
                    datatype : "json",
                    success:function(data) {
                        $('#categories').empty();
                        //console.log(data);
                        if(data.length != 0)
                        {
                            //console.log('in');
                            $.each(data, function(key, value) {
                               // console.log(data);
                                $('#categories').append('<option value="'+ value.id +'">'+ value.title +'</option>');
                                $('.selectpicker').selectpicker('refresh');
                            });
                        }else
                        {
                            $('#categories').empty();
                            $('.selectpicker').selectpicker('refresh');
                        }
                    }
                });
        }
    }

    function sendOrderToServer() 
    {
        var order = [];
        
        $('.order_row1').each(function(index,element) 
        {
            order.push({
                id: $(this).attr('data-id'),
                position: index+1
            });
        });

        $.ajax({
            type: "POST", 
            dataType: "json", 
            url: "<?php echo base_url("restaurant/items/sortabledatatable"); ?>",
            data: {
            order:order,
            },
            success: function(response) 
            {
                if (response.status == "success") {
                //console.log(response);
                } else {
                //console.log(response);
                }
            $("#latesttimestamp").attr('value', response.latesttimestamp);
                    var latesttimestamp = $("#latesttimestamp").val();
                   // alert(data.latesttimestamp);
                    if(restaurantid !="" && latesttimestamp !=""){
                        insertData(restaurantid, latesttimestamp);
                    }    
            }
        });
    }
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
   // $( document ).ready(function() {
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
            firebase.database().ref('Restaurants/' + restaurantid).set({
                LastUpdatedTime: latesttimestamp,
            });          
        }
      //  });
    </script>