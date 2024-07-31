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
        <li><a href="<?php echo base_url('restaurant/categories/index'); ?>">Categories</a></li>
        <li class="active"><?php echo $head; ?></li>
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
            Category
            </div>
                <div class="panel-body">
                <form class="fieldset-form" action="" method="post" id="category" enctype="multipart/form-data">
                    <fieldset>
                    <legend><?php echo $label; ?></legend>
                    <div class="form-group">
                    <label for="menus[]" class="form-label">Menus</label> <span class="require-field">*</span>
                        <select class="form-control selectpicker" tabindex="1" name="menus[]" id="menus" data-live-search="true" multiple>
                            <!-- <option value="">Select Menu</option> -->
                            <?php if (isset($menus) && !empty($menus)) 
                            {
                                foreach ($menus as $menu) {?>
                                <option value="<?php echo $menu['id'] ?>"
                                <?php if (isset($menu_ids) && !empty($menu_ids)) {
                                foreach ($menu_ids as $row) 
                                {
                                    if($row['menu_id'] === $menu['id'])
                                    {
                                       echo 'selected';
                                    }
                                }
                            } ?> ><?php echo htmlentities($menu['title']) ?></option>
                            <?php
                             }
                            } ?>
                        </select>
                        <div id="errorMsg"></div>
                    </div>
                    <div class="form-group">
                        <label for="title" class="form-label">Title</label> <span class="require-field">*</span>
                        <input type="text" class="form-control" id="title" tabindex="2" name="title" placeholder="Enter Title" value="<?php echo htmlentities((isset($category) && $category['title']) ? $category['title'] : set_value('title')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="arabian_title" class="form-label">Arabic</label> <span class="require-field"></span>
                        <input type="text" class="form-control" id="arabian_title" tabindex="3" name="arabian_title" placeholder="Enter Arabian Title" value="<?php echo htmlentities((isset($category) && $category['arabian_title']) ? $category['arabian_title'] : set_value('arabian_title')); ?>">
                    </div>

                        <div class="col-md-6">
                            <div class="form-group">
                            <?php
                                $required = 'required="required"';
                                if(isset($category['background_image']))
                                {
                                    $required = '';
                                }
                            ?>
                                <label for="backgroundimg" class="form-label">Background Image</label> <span class="require-field">*</span>
                                <input type="file" class="form-control" id="backgroundimg" tabindex="4"  name="backgroundimg" onchange="readBackground(this);" <?php echo $required; ?> />
                                <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                                <img id="blah1" src="<?php
                                if(isset($category['background_image']))
                                {
                                    $path = RESTAURANT_IMAGES . '/' . $restaurant_id. '/categories/backgrounds/'.$category['background_image'];
                                    if (CheckImageType($path)) 
                                    {
                                        echo base_url($path);
                                    } else 
                                    {   
                                        echo base_url(DEFAULT_IMG);  
                                    }
                                }else
                                {
                                    echo base_url(DEFAULT_IMG); 
                                } ?>" alt="image" width="100" height="100" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <?php
                                $required1 = 'required="required"';
                                if(isset($category['image']))
                                {
                                    $required1 = '';
                                }
                            ?>
                                <label for="categoryimage" class="form-label">Image</label> <span class="require-field">*</span>
                                <input type="file" class="form-control" id="categoryimage"  name="categoryimage" onchange="readURL(this);" <?php echo $required1; ?>  />
                                <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                                <img id="blah" src="<?php
                                if (isset($category['image'])) {
                                    $path1 = RESTAURANT_IMAGES . '/' . $restaurant_id. '/categories/'.$category['image'];
                                    if (CheckImageType($path1)) {
                                        echo base_url($path1);
                                    } else {
                                        echo base_url(DEFAULT_IMG);
                                    }
                                } else {
                                    echo base_url(DEFAULT_IMG);
                                }?>" alt="image" width="100" height="100" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    <button type="submit" class="btn btn-default">Submit</button>
                    <a href="<?php echo base_url('restaurant/categories'); ?>" class="btn btn-square">Cancel</a>
                    </fieldset>
                </form>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script src="assets/Backend/js/additional-methods.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#menus').selectpicker({noneSelectedText: 'Select Menus'});

        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 4 MB');

        $('.selectpicker').on( 'hide.bs.select', function ( ) {
            $(this).trigger("focusout");
        });
        
        $('#category').validate(
        {
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            rules:
            {
                "menus[]":{required:true},
                "title":{required:true,
                    normalizer: function(value) {
                    return $.trim(value);
                }},
                "arabian_title":{required:false,
                    normalizer: function(value) {
                    return $.trim(value);
                }},
                "backgroundimg":{ //2000000
                    extension: "png|jpg|jpeg",
                    filesize: 4000000},
                "categoryimage":{
                    extension: "png|jpg|jpeg",
                    filesize: 4000000},
            },
            messages:
            {
                "menus[]":{required:"This field is required"},
                "title":{required:"This field is required"},
                "arabian_title":{required:"This field is required"},
            },
            errorPlacement: function (error, element) {
                if(element.attr("name") == "menus[]") {
                    error.appendTo($('#errorMsg'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form)
            {
                $('button[type="submit"]').attr('disabled', true);
                form.submit();
            },
        });

        $('.selectpicker').selectpicker().change(function(){
            $(this).valid()
        });

        $('#backgroundimg').on('change', function() {
            $(this).valid();
        });
        $('#categoryimage').on('change', function() {
            $(this).valid();
        });
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

    function readBackground(input)
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            var valid_extensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (valid_extensions.test(input.files[0].name)) 
            {    
                $('#blah1')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>