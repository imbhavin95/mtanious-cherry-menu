<link href="assets/Backend/dropzone/dropzone.css" type="text/css" rel="stylesheet" />
<script src="assets/Backend/dropzone/dropzone.js"></script>
<style>
.item_img {
    border-radius: 20px;
    overflow: hidden;
    width: 120px;
    height: 120px;
    display: 'block';
    position: relative;
    z-index: 10;
}
.itemvideo{
    height: 110px;
}
.dz-progress {
  /* progress bar covers file name */
  display: none !important;
}
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
?>

<div class="page-header">
    <!-- <h1 class="title"><?php echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/items/index'); ?>">Items</a></li>
        <li class="active">Add</li>
    </ol>
</div>
<div class="container-widget">
<div class="col-md-12">
<div class="row">
        <div class="col-md-12">
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
        </div>
    </div>
      <div class="panel panel-default">
        <div class="panel-title">
          Item
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="items_form" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="col-md-12">
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                            <label for="menus" class="form-label">Menus</label> <span class="require-field">*</span>
                                <select class="selectpicker form-control" tabindex="1" placeholder="Select 1 or more options" name="menus" id="menus" data-live-search="true">
                                    <option value="">Select Menu</option>
                                    <?php if (isset($menus) && !empty($menus)) {
                                        foreach ($menus as $menu) {?>
                                        <option value="<?php echo $menu['id'] ?>" <?php echo (isset($selectedmenu) && ($selectedmenu[0]['menu_id'] === $menu['id'])) ? 'selected' : '' ; ?> ><?php echo htmlentities($menu['title']); ?></option>
                                    <?php
                                    }
                                    }?>
                                </select>
                                <div id="errorMsg"></div><?php echo form_error('menus')?>
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="form-group">
                            <label for="categories" class="form-label">Categories</label> <span class="require-field">*</span>
                                <select class="form-control selectpicker" tabindex="1" name="categories[]" id="categories" data-live-search="true" multiple>
                                   <?php 
                                        if(isset($categories) && !empty($categories))
                                        {
                                            foreach ($categories as $category) {?>
                                                <option value="<?php echo $category['id'] ?>"
                                                <?php if (isset($category_ids) && !empty($category_ids)) {
                                                    foreach ($category_ids as $row) 
                                                    {
                                                        if($row['category_id'] === $category['id'])
                                                        {
                                                            echo 'selected';
                                                        }
                                                    }
                                                } ?> ><?php echo htmlentities($category['title']); ?></option>
                                            <?php
                                            }
                                        }
                                    ?>
                                </select>
                                <div id="categories-errorMsg"></div>
                            </div>
                        </div>
                    </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="form-label">Title</label> <span class="require-field">*</span>
                            <input type="hidden" name="hidid" id="hidid" value="<?php echo (isset($item) && $item['id']) ? $item['id'] : '' ?>">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="<?php echo htmlentities((isset($item) && $item['title']) ? $item['title'] : set_value('title')); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="arabian_title" class="form-label">Arabic</label> <span class="require-field"></span>
                            <input type="text" class="form-control" id="arabian_title" name="arabian_title" placeholder="Enter Arabian Title" value="<?php echo htmlentities((isset($item) && $item['arabian_title']) ? $item['arabian_title'] : set_value('arabian_title')); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label> <span class="require-field">*</span>
                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php echo (isset($item) && $item['price']) ? number_format((float)$item['price'], 2, '.', '')  : set_value('price'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="calories" class="form-label">Calories</label>
                            <input type="text" class="form-control" id="calories" name="calories" placeholder="Enter Calories" value="<?php echo (isset($item) && $item['calories']) ? $item['calories'] : set_value('calories'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" style="resize: none;" id="description" name="description" placeholder="Enter Description"><?php echo htmlentities((isset($item) && $item['description']) ? $item['description'] : set_value('description')); ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="arabian_description" class="form-label">Arabic Description</label>
                        <textarea class="form-control" style="resize: none;" id="arabian_description" name="arabian_description" placeholder="Enter Arabian Description"><?php echo htmlentities((isset($item) && $item['arabian_description']) ? $item['arabian_description'] : set_value('arabian_description')); ?></textarea>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                   <label for="type" class="form-label">Types</label>
                    <select class="selectpicker form-control"  id="type" name="type[]" multiple>
                    <?php 
                    if(!empty($types))
                    {
                        foreach($types as $row) 
                        { ?>
                            <option value="<?php echo htmlentities($row['type']); ?>" 
                            <?php if(isset($item)){
                                foreach(explode(',',$item['type']) as $type)
                                {
                                    if($type === $row['type'])
                                    {
                                        echo 'selected';
                                    }
                                }
                            } ?> ><?php echo htmlentities($row['type']); ?></option>
                        <?php 
                        } 
                    }
                     ?>
                    </select>
                    <span id="error-type"></span><?php echo form_error('type')?>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="time" class="form-label">Minutes</label>
                        <input type="text" class="form-control" id="time"  name="time" placeholder="Enter Minutes" value="<?php echo (isset($item) && $item['time']) ? $item['time'] : set_value('time'); ?>">
                    </div>
                </div>
                
                <div class="form-group col-md-4">
                            <p class="margin-t-20">Is Dish New </p> 
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="is_dish_new1" value="1" tabindex="3" name="is_dish_new" <?php echo (isset($item) && $item['is_dish_new'] == 1) ? 'checked' : ''; ?> >
                                <label for="is_dish_new1"> Yes </label>
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="is_dish_new2" value="0" tabindex="4" <?php echo (!isset($item)) ? 'checked' : ''; ?>  name="is_dish_new" <?php echo (isset($item) && $item['is_dish_new'] == 0) ? 'checked' : ''; ?>>
                                <label for="is_dish_new2"> No </label>
                            </div> 
                    </div>
                <div class="form-group col-md-4">
                    <p class="margin-t-20">Is Dish Featured </p> 
                    <div class="radio radio-info radio-inline">
                        <input type="radio" id="feature1" value="1" tabindex="3" name="is_featured" <?php echo (isset($item) && $item['is_featured'] == 1) ? 'checked' : ''; ?> >
                        <label for="feature1"> Yes </label>
                    </div>
                    <div class="radio radio-inline">
                        <input type="radio" id="feature2" value="0" tabindex="4" <?php echo (!isset($item)) ? 'checked' : ''; ?>  name="is_featured" <?php echo (isset($item) && $item['is_featured'] == 0) ? 'checked' : ''; ?>>
                        <label for="feature2"> No </label>
                    </div> 
                </div>
                    <div class="form-group col-md-4">
                        <p class="margin-t-20">Is Price Show </p>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="priceshow1" value="1" tabindex="3" name="is_price_show" <?php echo (isset($item) && $item['is_price_show'] == 1) ? 'checked' : ''; ?> >
                            <label for="priceshow1"> Yes </label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="priceshow2" value="0" tabindex="4" <?php echo (!isset($item)) ? 'checked' : ''; ?>  name="is_price_show" <?php echo (isset($item) && $item['is_price_show'] == 0) ? 'checked' : ''; ?>>
                            <label for="priceshow2"> No </label>
                        </div>
                    </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <?php $noofimages=0; if(!empty($item_images)){ 
                                foreach ($item_images as $key => $row) {
                                    $imgpath;
                                    $videopath;
                                    
                                    $videodisplay = "display:none;";
                                    if(isset($row['image']) && (pathinfo($row['image'], PATHINFO_EXTENSION) == 'jpeg' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'JPEG' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'jpg' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'JPG' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'png' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'PNG' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'gif' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'GIF'))
                                    {
                                        $imgdisplay='';
                                        if (file_exists(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$row['item_id'].'/'.$row['image'])) 
                                        { 
                                            $imgpath = base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$row['item_id'].'/'.$row['image']);
                                        } else 
                                        {  
                                            $imgpath =  base_url(DEFAULT_IMG); 
                                        }
                                    }else if(isset($row['image']) && (pathinfo($row['image'], PATHINFO_EXTENSION) == 'mp4' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'MP4' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'wmv' || pathinfo($row['image'], PATHINFO_EXTENSION) == 'WMV'))
                                    {  
                                        $imgdisplay = "display:none;";
                                        $videodisplay = '';
                                        $videopath = base_url(RESTAURANT_IMAGES . '/' . $restaurant_id. '/items/' .$row['item_id'].'/'.$row['image']); 
                                    }else
                                    { 
                                        $imgpath =  base_url(DEFAULT_IMG); 
                                        $videopath =  base_url(DEFAULT_IMG); 
                                    } ?>
                                <div class="col-md-2" id="<?php echo $row['id']?>_img">
                                <img class="blah item_img" src="<?php echo isset($imgpath)? $imgpath  : ''; ?>" alt="image" width="100" height="100" style="<?php echo isset($imgdisplay) ? $imgdisplay : ''; ?>" /> 
                                <video controls
                                    class="itemvideo item_img"
                                    src="<?php echo isset($videopath) ? $videopath : '' ; ?>"
                                    width="100" height="100" style="<?php echo isset($videodisplay) ? $videodisplay : '' ; ?>">
                                    Sorry, your browser doesn't support embedded videos, <a href="<?php echo base_url(DEFAULT_IMG); ?>">download it</a> 
                                </video> 
                                <a href="javascript:void(0);" style="display: block;" id="rmv_file" data-id="<?php echo isset($row['id']) ? base64_encode($row['id']) : '' ?>">Remove File</a>
                                </div>
                            <?php $noofimages++; }
                            } ?>
                       
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div action="#" id="id_dropzone" class="dropzone"></div>
                        </div> <span class="help-block">Accepted formats:  png, jpg , jpeg, Mp4. Max image size 4 MB,The image dimension should not be less than 600px*600px and should not be more than 1024px*1024px, Max video size :50 MB</span>
                    </div>
                </div>

                  <button type="submit" id="btn_submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('restaurant/items'); ?>" class="btn btn-square">Cancel</a>
                </fieldset>
              </form>
            </div>
      </div>
    </div>
</div>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script src="assets/Backend/js/additional-methods.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    var site_urls = '<?php echo base_url(''); ?>';
    Dropzone.autoDiscover = false;
    var arr = [];
    i = 0;
    $(document).ready(function(){
        // $(".dz-progress").remove();
        $('#categories').selectpicker({noneSelectedText: 'Select Category'});

        $.validator.addMethod('time', function(value, element, param) {
            return value == '' || value.match(/^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/);
        }, 'Enter a valid time: hh:mm:ss');

        jQuery.validator.addMethod("validtime", function(value, element) {
        return this.optional( element ) || /^[0-9]{0,3}(\-[0-9]{1,3})?$/.test( value );
        }, 'Please enter a valid number.');

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 4 MB');

        (function($) {
            $.fn.numeric = function(options) {
                return this.each(function() {
                    var $this = $(this);
                    $this.keypress(options, function(e) {
                        // allow backspace and delete
                        if (e.which == 8 || e.which == 0) {
                            return true;
                        }
                        // allow float
                        if (e.which == 46 && this.value.length >=1 && this.value.indexOf('.') == -1) {
                            return true;
                        }
                        //if the letter is not digit
                        if (e.which < 48 || e.which > 57) return false;
                        // check max range
                        var dest = e.which - 48;
                        var result = this.value + dest.toString();
                        if (result > e.data.max) {
                            return false;
                        }
                    });
                });
            };
        })(jQuery);

        $('#price,#calories').numeric({
            max: 999999 
        });



        $('#items_form').validate(
        {
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            rules:
            {
                "menus":{required:true},
                "categories[]":{required:true},
                "title":{required:true},
                "arabian_title":{required:false},
                "price":{required:true,number:true,min:0.01},
                "calories":{number:true,min:1,maxlength:7},
                "is_favorite":{required:true},
                "time":{validtime:true},
            },
            messages:
            {
                "menus":{required:"This field is required"},
                "categories[]":{required:"This field is required"},
                "title":{required:"This field is required"},
                "arabian_limit":{required:"This field is required"},
                "price":{required:"This field is required"}
            },
            errorPlacement: function (error, element) {
                if(element.attr("name") == "menus") {
                    error.appendTo($('#errorMsg'));
                }else if(element.attr("name") == "categories[]") {
                    error.appendTo($('#categories-errorMsg'));
                }else if(element.attr("name") == "is_favorite") {
                    error.appendTo($('#favorite'));
                }else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form)
            {
                $('button[type="submit"]').attr('disabled', true);
                form.submit();
            },
        });

        // $('select[name="menus"]').on('change', function() {
        //     var menuID = $(this).val();
        //     var post_url = '<?php echo base_url() ?>restaurant/items/getCategories'
        //     if(menuID) {
        //         $.ajax({
        //             url: post_url,
        //             type: "POST",
        //             data : { "menuId" : menuID },
        //             datatype : "json",
        //             success:function(data) {
        //                 $('select[name="categories[]"]').empty();
        //                 $.each(data, function(key, value) {
        //                     console.log(key);
        //                     $('select[name="categories[]"]').append('<option value="'+ value.id +'">'+ value.title +'</option>');
        //                     $('.selectpicker').selectpicker('refresh');
        //                 });
        //             }
        //         });
        //     }else{
        //         $('select[name="categories[]"]').empty();
        //         $('.selectpicker').selectpicker('refresh');
        //     }
        // });
    });

    function readURL(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (valid_extensions.test(input.files[0].name)) 
            {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('click', '#rmv_file', function () {
       var image_id = $(this).attr('data-id');
        $.ajax({
            url: '<?php echo base_url("restaurant/Items/delete_images/"); ?>' + image_id,
            type: "GET",
            success: function (data) {
                result = JSON.parse(data);
                //console.log('#'+result.id+'_img'); //74_img
                arr[i++] = result.id;
                $('#'+result.id+'_img').hide();
                console.log(arr);
                location.reload();
            }
        });
    });

    $("#id_dropzone").dropzone({
        paramName: "itemimage",
        maxFilesize: 50,
        maxThumbnailFilesize: 10,
        dictDefaultMessage: 'Drop files to upload <span>or CLICK</span>',
        addRemoveLinks: true,
        autoProcessQueue: false,
        parallelUploads: 1,
        maxFiles: 3-<?php echo $noofimages; ?>,
        minFiles: 50,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.mp4,.avi,.wmv",
            accept: function(file, done) {
                var ext=file.name.split('.').pop();
                //console.log(file);
                //console.log(ext);
                var imageext = ["jpeg","jpg","png","gif"];
                var imageresult = imageext.includes(ext);
                //console.log("Image",imageresult);
                //console.log("File Size",file.size);
                if(imageresult && file.size>4000001){
                 // alert('File is too Big! maxFilesize: 2mb');
                         myDropzone.removeFile(file);
                         return done('File is too Big! maxFilesize: 4mb');
                }
                var videoext = ["mp4","avi","wmv"];
                var videoresult = videoext.includes(ext);
                //console.log("Video",videoresult);
                if(videoresult && file.size>50000001){
                  //alert('File is too Big! maxFilesize: 10mb');
                         myDropzone.removeFile(file);
                         return done('File is too Big! maxFilesize: 50mb');
                }

                 return done();


                },
        init: function () 
        {
            var submitButton = document.querySelector("#btn_submit");
            myDropzone = this;
            submitButton.addEventListener("click", function (a) 
            {
                // if (myDropzone.files.length == 0) 
                // {
                //     swal({
                //         title: "Please upload atleast one image!",
                //         confirmButtonColor: "#2196F3",
                //         type: "info"
                //     });
                // }

                if ($('#items_form').valid()) 
                {
                    console.log('valid forrm');
                    $('.loading').show() //-- Display loader
                    $('#btn_submit').prop('disabled', true);
                    var formElement = document.querySelector("#items_form");
                    var fd = new FormData(formElement);
                    var item_id = $('#hidid').val();
                    if($('#hidid').val().length > 0)
                    {
                        ajax_url = site_urls + 'restaurant/items/edit/'+ btoa(item_id);
                        console.log('edit');
                    }
                    else
                    {
                        ajax_url = site_urls + 'restaurant/items/add';
                    }
                    console.log('before ajax');
                    a.preventDefault();
                    $.ajax({
                        url: ajax_url,
                        type: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            data = JSON.parse(response);
                            console.log(data);
                            if (data.insert_id != '') {
                                if (myDropzone.files.length > 0) {
                                    myDropzone.options.autoProcessQueue = true;
                                    myDropzone.options.url = site_urls + "restaurant/items/upload_images/" + data.insert_id;
                                    myDropzone.processQueue();
                                } else {
                                    window.location.href = '<?php echo site_url('restaurant/items') ?>';
                                }
                            } else {
                                if (data.insert_id == 0)
                                {
                                    $('.loading').hide();
                                    alert('Spot exists! Please upload another spot!');
                                    $('#btn_submit').prop('disabled', false);  //--added by KU
                                    $("html, body").animate({scrollTop: 0}, "slow");
                                } else
                                    alert('Failed to upload, Please try again later!');
                            }
                        }
                    });
                    console.log('after ajax');
                } 
            });
 
            myDropzone.on("error", function (file,message) {
                       alert(message);
                        myDropzone.removeFile(file);
                    }); 


            myDropzone.on("complete", function (file) 
            {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    if (!file.accepted) {
                        return false;
                    }
                  //$('.div_loader').hide();
                    myDropzone.removeFile(file);
                    window.location.href = '<?php echo site_url('restaurant/items') ?>';
                }
            });
        },
    });
</script>