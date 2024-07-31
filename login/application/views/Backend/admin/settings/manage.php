<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/home/index'); ?>">Dashboard</a></li>
        <li class="active">Settings</li>
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
        Background Setting
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="backgroundsetting" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                
                  <div class="form-group">
                  <?php
                        $required = 'required="required"';
                        if(isset($settings['bg_before_login']))
                        {
                            $required = '';
                        }
                    ?>
                    <label for="backgroundimage" class="form-label">Background Image</label><span class="require-field">*</span>
                    <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo isset($settings) ? $settings['id'] : '' ?>" />
                    <input type="file" class="form-control" id="backgroundimage"  name="backgroundimage" onchange="readURL(this);" <?php echo $required; ?> /> <span class="help-block">Accepted formats:  png, jpg , jpeg.</span>
                    <?php //echo base_url(USER_IMAGES.$restaurant['image']); ?>
                    <img id="blah" src="<?php
                    if(isset($settings['bg_before_login']))
                    {
                        if (file_exists(DEFAULT_BACKGROUND.$settings['bg_before_login'])) 
                        {
                            echo base_url(DEFAULT_BACKGROUND . $settings['bg_before_login']);
                        } else 
                        {   
                            echo base_url(DEFAULT_IMG);  
                        }
                    }else
                    {
                        echo base_url(DEFAULT_IMG); 
                    } ?>" alt="image" width="100" height="100" />
                  </div>
                  <div class="form-group col-md-6">
                                    
                                    <label for="rest_name" class="form-label">Android Version</label><span class="require-field">*</span>
                                    <input type="text" class="form-control"  id="android_version" tabindex="2" name="android_version" value="<?php echo $app_version;?>"   required  />  
                                    <br>
                                    
                                </div>
                  <div class="clearfix"></div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('admin/backgroundsetting'); ?>" class="btn btn-square">Cancel</a>
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

    $('#backgroundsetting').validate(
    {
        rules:
        {
            "backgroundimage":{
                extension: "png|jpg|jpeg",
                filesize: 2000000},
        },
        submitHandler: function (form)
        {
            $('button[type="submit"]').attr('disabled', true);
            form.submit();
        },
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
</script>