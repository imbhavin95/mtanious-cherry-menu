<?php
    if(is_sub_admin())
    {
        $restaurant_id = $this->session->userdata('login_user')['restaurant_id'];
    }
    else
    {
        $restaurant_id = $this->session->userdata('login_user')['id'];
    }
?>

<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/Home'); ?>">Dashboard</a></li>
        <li><a href="<?php echo base_url('restaurant/items'); ?>">Items</a></li>
        <li class="active">Item Images</li>
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
    <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="restaurant_enable" style="display:none">
        <div id="rest_enable"></div>
    </div>
    <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="restaurant_disable" style="display:none">
        <div id="rest_disable"></div>
    </div>
    <div class="panel panel-default">
        <div class="panel-title">
          Item Images
        </div>
            <div class="panel-body">
              <form id="itemImages" action="<?php echo (isset($imgId))? base_url('restaurant/ItemImages/edit/'.$imgId) : base_url('restaurant/ItemImages/add') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="itemimage" class="form-label">Image / Video</label> <span class="require-field">*</span>
                    <input type="hidden" id="itemId" name="itemId" value="<?php echo (isset($itemId))? $itemId : ''; ?>" />
                    <input type="hidden" id="imgId" name="imgId" value="<?php echo (isset($imgId))? $imgId : ''; ?>" />
                    <?php
                        $required = 'required="required"';
                        if(isset($item['image']))
                        {
                            $required = '';
                        }
                    ?>
                    <input type="file" class="form-control"  id="itemimage"  name="itemimage" onchange="readURL(this);" <?php echo $required ?> />
                    <span class="help-block">Accepted formats:  png, jpg , jpeg, Mp4, avi. Max file size 2 MB</span>

                    <?php
                    $imgpath;
                    $videopath;
                    $imgdisplay;
                    $videodisplay = "display:none";
                    if(isset($item['image']) && (pathinfo($item['image'], PATHINFO_EXTENSION) == 'jpeg' || pathinfo($item['image'], PATHINFO_EXTENSION) == 'jpg' || pathinfo($item['image'], PATHINFO_EXTENSION) == 'png' || pathinfo($item['image'], PATHINFO_EXTENSION) == 'gif'))
                    {
                        
                        if (file_exists(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$item['item_id'].'/'.$item['image'])) 
                        { 
                            $imgpath = base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$item['item_id'].'/'.$item['image']);
                        } else 
                        {  
                            $imgpath =  base_url(DEFAULT_IMG); 
                        }
                    }else if(isset($item['image']) && (pathinfo($item['image'], PATHINFO_EXTENSION) == 'mp4' || pathinfo($item['image'], PATHINFO_EXTENSION) == 'avi' || pathinfo($item['image'], PATHINFO_EXTENSION) == 'wmv'))
                    {  
                        $imgdisplay = "display:none";
                        $videodisplay = '';
                        $videopath = base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$item['item_id'].'/'.$item['image']); 
                    }else
                    { 
                        $imgpath =  base_url(DEFAULT_IMG); 
                        $videopath =  base_url(DEFAULT_IMG); 
                    } ?>
                   <img class="blah" src="<?php echo isset($imgpath)? $imgpath  : ''; ?>" alt="image" width="100" height="100" style="<?php echo isset($imgdisplay) ? $imgdisplay : ''; ?>" /> 
                   <video controls
                        class="itemvideo"
                        src="<?php echo isset($videopath) ? $videopath : '' ; ?>"
                        width="300" style="<?php echo isset($videodisplay) ? $videodisplay : '' ; ?>">
                        Sorry, your browser doesn't support embedded videos, <a href="<?php echo base_url(DEFAULT_IMG); ?>">download it</a> 
                    </video>
                </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('restaurant/ItemImages/index/'.$itemId); ?>" class="btn btn-square">Cancel</a>
              </form>
            </div>
      </div>
    <div class="panel panel-default">
        <div class="panel-body table-responsive">
            <table id="item_images" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
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
<script src="assets/Backend/js/additional-methods.min.js"></script>
<script>
var image_extensions = /(\.jpg|\.jpeg|\.png)$/i;
var video_extensions = /(\.mp4|\.avi|\.wmv)$/i;
    $(document).ready(function() {
        $(function () {
            $('#item_images').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                searching:false,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                order: [[3, "desc"]],
                ajax: '<?php echo base_url("restaurant/ItemImages/get_item_images/".$itemId); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "image",
                        visible: true,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var img = '';
                            if (data != null) {
                                if(image_extensions.test(data))
                                {
                                    img = '<a class="fancybox" href="<?php echo base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/'); ?>'+full.item_id+'/'+data+'"" data-fancybox-group="gallery" ><img src="<?php echo base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/'); ?>'+full.item_id+'/'+data+'" height="55px" width="55px" alt="' + full.title + '" class="img-circle"/></a>';
                                }else if(video_extensions.test(data))
                                {
                                    img = '<video controls src="<?php echo base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/'); ?>'+full.item_id+'/'+data+'"" height="55px" width="55px"> Sorry Your Browser Not Support</video>';
                                }
                            } else {
                                img = '<a class="fancybox" href="<?php echo base_url(DEFAULT_IMG)?>" data-fancybox-group="gallery" ><img src="<?php echo base_url(DEFAULT_IMG) ?>" height="55px" width="55px" alt="' + full.title + '" class="img-circle"/></a>';
                            }
                            return img;
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
                                action +='<div class="btn-group">'
                                action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="restaurant/ItemImages/edit/' + btoa(full.id) + '">Edit</a></li>'
                                action +='<li><a href="'+ deleteurl +'restaurant/ItemImages/delete/'+ btoa(full.id) + '" onclick="return confirm_alert(this)">Delete</a></li>'
                                action +='</ul>'
                                action +='</div>'
                            return action;
                        }
                    }
                ]
            });
        });

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 2 MB');

        $('#itemImages').validate(
        {
            rules:
            {
                "itemimage":{
                extension: "mp4|avi|flv|wmv|png|jpg|jpeg|gif",
                filesize: 2000000 },
            },
            submitHandler: function (form)
            {
                $('button[type="submit"]').attr('disabled', true);
                form.submit();
            },
        });
    });

     function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this item image/video!",
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
                swal("Cancelled", "Your item image/video is safe :)", "error");
            }
        });
        return false;
    }

    $(document).on('click', '.chkbox', function () {
        $('#restaurant_disable').hide();
        $('#restaurant_enable').hide();
        var user_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/ItemImages/change_status"); ?>',
            type: "POST",
            data: {id: user_id},
            success: function (data) {
                if(data.status == 1)
                {
                    $('#rest_disable').html(data.msg);
                    $('#restaurant_disable').show();
                    setTimeout(function(){ $('#restaurant_disable').hide() }, 3000);
                }else{
                    $('#rest_enable').html(data.msg);
                    $('#restaurant_enable').show();
                    setTimeout(function(){ $('#restaurant_enable').hide() }, 3000);
                }
            }
        });
    });

    function readURL(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            if (image_extensions.test(input.files[0].name)) 
            {
                $('.blah').show();
                $('.itemvideo').hide();
                $('.blah')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            }else if(video_extensions.test(input.files[0].name))
            {
                $('.blah').hide();
                $('.itemvideo').show();
                var fileUrl = window.URL.createObjectURL(input.files[0]);
                $(".itemvideo").attr("src", fileUrl);
            }

            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>