<!DOCTYPE html>
<?php 
$base_url = base_url();

if($_SERVER['HTTP_HOST'] == 'cherrymenu.com')
{
    if($_SERVER['REQUEST_URI'] == '/login/')
    {
        header('Location: '. $base_url);
    }
    else if($_SERVER['REQUEST_URI'] == '/login/login/')
    {
        header('Location: '. $base_url);
    }
    else if($_SERVER['REQUEST_URI'] == '/login/login')
    {
        header('Location: '. $base_url);
    }   

    else if($_SERVER['REQUEST_URI'] == '/login/Login/')
    {
        header('Location: '. $base_url);
    } 
    else if($_SERVER['REQUEST_URI'] == '/login/Login')
    {
        header('Location: '. $base_url);
    } 
}
else if($_SERVER['HTTP_HOST'] == 'www.cherrymenu.com')
{
    if($_SERVER['REQUEST_URI'] == '/login/login/')
    {
       header('Location: '. $base_url);
    }
    
    if($_SERVER['REQUEST_URI'] == '/login/Login/')
    {
       header('Location: '. $base_url);
    }


    if($_SERVER['REQUEST_URI'] == '/login/login')
    {
       header('Location: '. $base_url);
    }
    
    if($_SERVER['REQUEST_URI'] == '/login/Login')
    {
       header('Location: '. $base_url);
    }
}



?>


<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Looking for Natty Digital Menu Boards? Login to Cherrymenu</title>

    <meta name="description" content=" Looking for a digital menu board that can amplify your guests’ gratification? Login to Cherrymenu to pick a sassy digital display for your restaurant.">

    <meta name="keywords" content="" />

    <link rel="icon" href="https://www.cherrymenu.com/img/faviconcherry.jpg" type="image/gif" sizes="16x16">
   <!--  <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
    </noscript>   -->
    <base href="<?php echo base_url(); ?>">
    <!-- css -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet"> -->

    <link href="<?php echo base_url('assets/Backend/css/root.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/Backend/css/custome.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/Backend/validation_jquery/css/cmxform.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/Backend/validation_jquery/css/cmxformTemplate.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/Backend/validation_jquery/css/core.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/Backend/validation_jquery/css/reset.css'); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/Backend/validation_jquery/css/screen.css'); ?>" rel="stylesheet" type="text/css" />






    <style type="text/css">
     body{    
    background-image : url('assets/Backend/img/cherrymenu-login-bg.jpg') ;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 10% 70%;
    font-family: 'Avenir LT Std', sans-serif !important;
    position: relative;
}
body div , body p , body h2 {font-family: 'Avenir LT Std', sans-serif !important;}

body:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}
        html{
                height: 100%
            }
    </style>    
</head>
<body>
    <div class="login-form">
        <form action="" method="post" id="form">
            <div class="top">
                <img src="<?php echo base_url('assets/Backend/img/cerrymenulogo.svg') ?>" alt="icon" class="icon">
                <h2>Login</h2>
            </div>
            <div class="form-area">
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
                <div class="group">
                    <input type="text" name="email" id="email" class="form-control" placeholder="Username">
                    <i class="fa fa-user"></i>
                </div>
                <div class="group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    <i class="fa fa-key"></i>
                </div>
                <div class="checkbox checkbox-primary">
                      <input type="checkbox" class="styled" name="remember_me" id="remember_me" value="1">
                    <label for="remember_me">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-default btn-block">LOGIN</button>
                <div style="text-align: center;padding-top: 10px;">
                    <a class="forgot-password" href="<?php echo base_url('forgot-password'); ?>"> Forgot password?</a>
                </div>
            </div>
        </form>
        <div class="footer-links row">
            <div class="col-xs-12 text-center" style="color:white">Don't have an account? <a href="<?php echo base_url('registration'); ?>" style="color:white"><b><u>Try cherrymenu for free</u></b></a></div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript" src="<?= $base_url('assets/Backend/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $('#form').validate(
    {
        rules:
        {
         "email":{required:true,email:true},
         "password":{required:true,}
        },
        messages:
        {
            "email":{required:"This field is required",email:"Please enter a valid E-mail"},
            "password":{required:"This field is required"}
        },
        submitHandler: function (form) 
        {
            $('button[type="submit"]').attr('disabled', true);
            $('.alert').hide();
            form.submit();
        },
    });
});

setTimeout(function(){ $('.alert').hide() }, 3000);
</script> 