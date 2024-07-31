<?php $userType = ($this->session->userdata('login_user')['role'] === ADMIN) ? ADMIN : RESTAURANT; ?>
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
        <li><a href="<?php echo base_url($userType.'/Home'); ?>">Dashboard</a></li>
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
            <div class="col-md-12 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-title">
                        Basic Information
                    </div>
                    <div class="panel-body">
                        <form method="post" id="profile" enctype="multipart/form-data" action="<?php echo base_url($userType.'/Home/profile') ?>">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label><span class="require-field">*</span>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="<?php echo htmlentities(isset($user) ? $user['name'] : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label><span class="require-field">*</span>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo isset($user) ? $user['email'] : '' ?>">
                            </div>
                            <div class="form-group">
                                <label for="profileimg" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="profileimg" name="profileimg"  onchange="readURL(this);"><span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                                <img id="blah" src="<?php
                                if (isset($user['image'])) {
                                    if(is_sub_admin())
                                    {
                                        $path = RESTAURANT_IMAGES. '/' . $restaurant_id. '/users/' . $this->session->userdata('login_user')['id'] . '/'.$user['image'];
                                    }else
                                    {
                                        $path = RESTAURANT_IMAGES. '/' .$user['id']. '/' .$user['image'];
                                    }
                                    if(CheckImageType($path))  {
                                        echo base_url($path);
                                    } else {
                                        echo base_url(DEFAULT_USER_IMG);
                                    }
                                } else {
                                    echo base_url(DEFAULT_USER_IMG);
                                }?>" alt="image" width="100" height="100" />
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                            <button type="reset" class="btn btn-square">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>  
            <div class="col-md-12 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-title">
                    Change Password
                    </div>
                    <div class="panel-body">
                        <form id="chngpswd" method="post" action="<?php echo base_url('restaurant/home/reset_password'); ?>">
                            <div class="form-group">
                                <label for="oldpassword" class="form-label">Old Password</label><span class="require-field">*</span>
                                <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Enter Old Password">
                            </div>
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label><span class="require-field">*</span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">Confirm Password</label><span class="require-field">*</span>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter Confirm Password">
                            </div>
                            
                            <button type="submit" class="btn btn-default">Submit</button>
                            <button type="reset" class="btn btn-square">Cancel</button>
                        </form>
                    </div>
                </div>
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

        $('#profile').validate(
        {
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            rules:
            {
                "name":{required:true, 
                normalizer: function(value) {
                return $.trim(value);
            }},
                "email":{required:true,email:true,
                    remote: {
                    url: '<?php echo base_url('restaurant/Home/checkUniqueEmail'); ?>',
                    type: "post",
                    data: {
                        email: function () {
                            return $("#email").val();
                        },
                        user_id: '<?php echo isset($user['id']) ? base64_encode($user['id']) : "" ?>',
                    },
                }
                },
                "profileimg":{
                extension: "png|jpg|jpeg",
                filesize: 4000000},
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

        $('#chngpswd').validate(
        {
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            rules:
            {
                "oldpassword":{required:true,            
                remote: {
                    url: '<?php echo base_url('restaurant/Home/checkOldPassword'); ?>',
                    type: "post",
                    data: {
                        password : function () {
                            return $("#oldpassword").val();
                        },
                        user_id: '<?php echo isset($user['id']) ? base64_encode($user['id']) : "" ?>',
                    },
                }},
                "password":{required:true,minlength: 6},
                "confirm_password":{required:true,minlength: 6,equalTo: "#password"},
            },
            messages:
            {
                "oldpassword":{required:"This field is required",remote: "The password is wrong!."},
                "password":{required:"This field is required"},
                "confirm_password":{required:"This field is required"},
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