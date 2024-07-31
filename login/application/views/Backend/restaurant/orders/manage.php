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
        <li><a href="<?php echo base_url('restaurant/menus/index'); ?>">Menus</a></li>
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
          Menu
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="menu" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="form-group">
                    <label for="title" class="form-label">Title</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="title" name="title" tabindex="1" placeholder="Enter Title" value="<?php echo htmlentities((isset($menu) && $menu['title']) ? $menu['title'] : set_value('title')); ?>">
                  </div>
                  <div class="form-group">
                    <label for="arabian_title" class="form-label">Arabic</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="arabian_title" tabindex="2" name="arabian_title" placeholder="Enter Arabic Title" value="<?php echo htmlentities((isset($menu) && $menu['arabian_title']) ? $menu['arabian_title'] : set_value('arabian_title')); ?>">
                  </div>
                  <div class="form-group">
                        <p class="margin-t-20">Disable Feedback <span class="require-field">*</span></p> 
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="inlineRadio1" value="1" tabindex="3" name="is_disable_feedback" <?php echo (isset($menu) && $menu['is_disable_feedback'] == 1) ? 'checked' : ''; ?> >
                            <label for="inlineRadio1"> Yes </label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="inlineRadio2" value="0" tabindex="4" <?php echo (!isset($menu)) ? 'checked' : ''; ?> name="is_disable_feedback" <?php echo (isset($menu) && $menu['is_disable_feedback'] == 0) ? 'checked' : ''; ?>>
                            <label for="inlineRadio2"> No </label>
                        </div> 
                        <div id="feedback"></div>
                    </div>
                    <?php
                        $required = 'required="required"';
                        if(isset($menu['background_image']))
                        {
                            $required = '';
                        }
                    ?>
                    <div class="form-group">
                        <label for="backgroundimg" class="form-label">Background Image</label> <span class="require-field">*</span>
                        <input type="file" class="form-control" id="backgroundimg" tabindex="5"  name="backgroundimg" onchange="readBackground(this);" <?php echo $required; ?> />
                        <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 2 MB</span>
                        <img id="blah" src="<?php
                        if(isset($menu['background_image']))
                        {
                            $path = RESTAURANT_IMAGES . '/' . $restaurant_id. '/menus/backgrounds/'.$menu['background_image'];
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
                
                    <div class="clearfix"></div>
                  <button type="submit" class="btn btn-default" tabindex="7">Submit</button>
                  <a href="<?php echo base_url('restaurant/menus'); ?>" class="btn btn-square" tabindex="8">Cancel</a>
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
    $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 2 MB');

    $('#menu').validate(
    {
        rules:
        {
            "title":{required:true,
                normalizer: function(value) {
                return $.trim(value);
                }},
            "arabian_title":{required:true,
                normalizer: function(value) {
                return $.trim(value);
                }},
            "is_disable_feedback":{required:true},
            "backgroundimg":{
                extension: "png|jpg|jpeg",
                filesize: 2000000},
        },
        messages:
        {
            "title":{required:"This field is required"},
            "arabian_title":{required:"This field is required"},
        },
        errorPlacement: function (error, element) {
            if(element.attr("name") == "is_disable_feedback") {
                error.appendTo($('#feedback'));
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
});

function readBackground(input)
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

</script>

