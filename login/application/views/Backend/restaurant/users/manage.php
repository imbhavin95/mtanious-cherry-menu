<div class="page-header">
    <!-- <h1 class="title"><?php //echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/users/index'); ?>">Users</a></li>
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
          Users
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="user" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="form-group">
                  
                    <label for="name" class="form-label">Name</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" tabindex="1" value="<?php echo htmlentities((isset($user) && $user['name']) ? $user['name'] : set_value('name')); ?>">
                  </div>
                  <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" tabindex="2" value="<?php echo (isset($user) && $user['email']) ? $user['email'] : set_value('email'); ?>">
                  </div> 
                  <div class="form-group">
                        <p class="margin-t-20">User Type <span class="require-field">*</span> </p> 
                        <div class="radio radio-inline">
                            <input type="radio" id="sub_admin" value="<?php echo SUB_ADMIN; ?>" tabindex="3" name="role" <?php echo (isset($user) && $user['role'] == SUB_ADMIN) ? 'checked' : ''; ?> >
                            <label for="sub_admin"> Sub Admin </label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="staff_role" <?php echo (!isset($user)) ? 'checked' : ''; ?> value="<?php echo STAFF; ?>" tabindex="4" name="role" <?php echo (isset($user) && $user['role'] == STAFF) ? 'checked' : ''; ?>>
                            <label for="staff_role"> Staff </label>
                        </div>
                        </br> <span id="error-userrole"></span>
                    </div>   
                  <div class="form-group">
                    <label for="profileimage" class="form-label">Image</label>
                    <input type="file" class="form-control" id="profileimage"  name="profileimage" tabindex="6" onchange="readURL(this);"/>
                    <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                    <img id="blah" src="<?php
                    if(isset($user['image']))
                    {
                        $path = RESTAURANT_IMAGES . '/' . $this->session->userdata('login_user')['id']. '/users/'.$user['id'].'/'.$user['image'];
                        if (CheckImageType($path)) 
                        {
                            echo base_url($path);
                        } else 
                        {   
                            echo base_url(DEFAULT_USER_IMG);  
                        }
                    }else
                    {
                        echo base_url(DEFAULT_USER_IMG); 
                    } ?>" alt="image" width="100" height="100" />
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('restaurant/users'); ?>" class="btn btn-square">Cancel</a>
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
        }, 'File size must be less than 4 MB');

    jQuery.validator.addMethod("lettersonly", function(value, element) 
    {
    return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Letters and spaces only please");

    $('#user').validate(
    {
        rules:
        {
            "name":{required:true,
                normalizer: function(value) {
                return $.trim(value);
                }},
            "email":{required:'#sub_admin[value="sub_admin"]:checked',email:true,
                remote: {
                    url: '<?php echo base_url('restaurant/users/checkUniqueEmail'); ?>',
                    type: "post",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        user_id: '<?php echo isset($user['id']) ? base64_encode($user['id']) : "" ?>',
                    },
                }
                },
            "role":{required:true},
            "profileimage":{
                extension: "png|jpg|jpeg",
                filesize: 4000000},
        },
        errorPlacement: function (error, element) {
                if(element.attr("name") == "role") {
                    error.appendTo($('#error-userrole'));
                }else {
                    error.insertAfter(element);
                }
        },
        messages:
        {
            "name":{required:"This field is required"},
            "email":{required:"This field is required",remote: "The email is already used."},
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
                $('#profileimage').attr('required',false);
                $('#profileimage-error').hide();
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