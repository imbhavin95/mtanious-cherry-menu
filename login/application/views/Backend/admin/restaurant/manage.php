<div class="page-header">
   
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/restaurant/index'); ?>">Restaurants</a></li>
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
          Restaurant
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="restaurant" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="form-group">
                    <label for="name" class="form-label">Name</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="name" tabindex="1" name="name" placeholder="Enter Name" value="<?php echo htmlentities((isset($restaurant) && $restaurant['name']) ? $restaurant['name'] : set_value('name')); ?>">
                  </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label> <span class="require-field">*</span>
                            <input type="text" class="form-control" id="email" tabindex="2" name="email" placeholder="Enter Email" value="<?php echo (isset($restaurant) && $restaurant['email']) ? $restaurant['email'] : set_value('email'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="devices_limit" class="form-label">Devices Limit</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="devices_limit" tabindex="2" name="devices_limit" placeholder="Enter Devices Limit" value="<?php echo (isset($restaurant) && $restaurant['devices_limit']) ? $restaurant['devices_limit'] : set_value('devices_limit'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="users_limit" class="form-label">User's Limit</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" tabindex="3" name="users_limit" id="users_limit" placeholder="Enter User's Limit" value="<?php echo (isset($restaurant) && $restaurant['users_limit']) ? $restaurant['users_limit'] : set_value('users_limit'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="menus_limit" class="form-label">Menus Limit</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" name="menus_limit" tabindex="4" id="menus_limit" placeholder="Enter Menus Limit" value="<?php echo (isset($restaurant) && $restaurant['menus_limit']) ? $restaurant['menus_limit'] : set_value('menus_limit'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="categories_limit" class="form-label">Categories Limit</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" name="categories_limit" tabindex="5" id="categories_limit" placeholder="Enter Categories Limit" value="<?php echo (isset($restaurant) && $restaurant['categories_limit']) ? $restaurant['categories_limit'] : set_value('categories_limit'); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="items_limit" class="form-label">Items Limit</label> <span class="require-field">*</span>
                            <input type="number" class="form-control"  name="items_limit" tabindex="6" id="items_limit" placeholder="Enter Items Limit" value="<?php echo (isset($restaurant) && $restaurant['items_limit']) ? $restaurant['items_limit'] : set_value('items_limit'); ?>">
                        </div>
                    </div>  
                       
                  <div class="form-group">
                    <?php
                        $required = 'required="required"';
                        if(isset($restaurant['image']))
                        {
                            $required = '';
                        }
                    ?>
                    <label for="profileimage" class="form-label">Image</label> <span class="require-field"></span>
                    <input type="file" class="form-control" id="profileimage"  tabindex="7" name="profileimage" onchange="readURL(this);" <?php //echo $required; ?>/> <span class="help-block">Accepted formats:  png, jpg , jpeg.</span>
                    <?php //echo base_url(USER_IMAGES.$restaurant['image']); ?>
                    <img id="blah" src="<?php
                    if(isset($restaurant['image']))
                    {
                        $path = RESTAURANT_IMAGES. '/' .$restaurant['id']. '/' .$restaurant['image'];
                      
                        if (CheckImageType($path)) 
                        {
                            echo base_url(RESTAURANT_IMAGES . '/' .$restaurant['id']. '/' . $restaurant['image']);
                        } else 
                        {   
                            echo base_url(DEFAULT_USER_IMG);  
                        }
                    }else
                    {
                        echo base_url(DEFAULT_USER_IMG); 
                    } ?>" alt="image" width="100" height="100" />
                  </div>
                  <div class="form-group">
                        <p class="margin-t-20">Enable Ordering Feature <span class="require-field">*</span></p> 
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="inlineRadio1" value="1" tabindex="3" name="order_feature" <?php echo (isset($restaurant) && $restaurant['order_feature'] == 1) ? 'checked' : ''; ?> >
                            <label for="inlineRadio1"> Yes </label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="inlineRadio2" value="0" tabindex="4" <?php echo (!isset($restaurant)) ? 'checked' : ''; ?> name="order_feature" <?php echo (isset($restaurant) && $restaurant['order_feature'] == 0) ? 'checked' : ''; ?>>
                            <label for="inlineRadio2"> No </label>
                        </div> 
                        <div id="feedback"></div>
                    </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('admin/restaurant'); ?>" class="btn btn-square">Cancel</a>
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

    $('#restaurant').validate(
    {
        rules:
        {
            "name":{required:true, 
                normalizer: function(value) {
                return $.trim(value);
            }},
            "email":{required:true,email:true,
                remote: {
                    url: '<?php echo base_url('admin/restaurant/checkUniqueEmail'); ?>',
                    type: "post",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        restaurant_id: '<?php echo isset($restaurant['id']) ? base64_encode($restaurant['id']) : "" ?>',
                    },
                }
                },
            "devices_limit":{required:true,number:true,digits: true},
            "users_limit":{required:true,number:true,digits: true,min:1,
                remote: {
                    url: '<?php echo base_url('admin/restaurant/checkUserLimit'); ?>',
                    type: "post",
                    data: {
                        users_limit: function () {
                            return $("#users_limit").val();
                        },
                        restaurant_id: '<?php echo isset($restaurant['id']) ? base64_encode($restaurant['id']) : "" ?>',
                    },
                }
            },
            "menus_limit":{required:true,number:true,digits: true,min:1,
                remote: {
                    url: '<?php echo base_url('admin/restaurant/checkMenuLimit'); ?>',
                    type: "post",
                    data: {
                        menus_limit: function () {
                            return $("#menus_limit").val();
                        },
                        restaurant_id: '<?php echo isset($restaurant['id']) ? base64_encode($restaurant['id']) : "" ?>',
                    },
                }
                },
            "categories_limit":{required:true,number:true,digits: true,min:1,
                remote: {
                    url: '<?php echo base_url('admin/restaurant/checkCategoryLimit'); ?>',
                    type: "post",
                    data: {
                        categories_limit: function () {
                            return $("#categories_limit").val();
                        },
                        restaurant_id: '<?php echo isset($restaurant['id']) ? base64_encode($restaurant['id']) : "" ?>',
                    },
                }
            },
            "items_limit":{required:true,number:true,digits: true,min:1,
                remote: {
                    url: '<?php echo base_url('admin/restaurant/checkItemLimit'); ?>',
                    type: "post",
                    data: {
                        items_limit: function () {
                            return $("#items_limit").val();
                        },
                        restaurant_id: '<?php echo isset($restaurant['id']) ? base64_encode($restaurant['id']) : "" ?>',
                    },
                }
            },
            "profileimage":{
                extension: "png|jpg|jpeg",
                filesize: 2000000},
        },
        messages:
        {
            "name":{required:"This field is required"},
            "email":{required:"This field is required",remote: "The email is already used."},
            "users_limit":{required:"This field is required",remote: "The users are already available."},
            "menus_limit":{required:"This field is required",remote: "The menus are already available."},
            "categories_limit":{required:"This field is required",remote: "The categories are already available."},
            "items_limit":{required:"This field is required",remote: "The items are already available."},
           
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