<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kode is a Premium Bootstrap Admin Template, It's responsive, clean coded and mobile friendly">
    <meta name="keywords" content="bootstrap, admin, dashboard, flat admin template, responsive," />
    <title><?php echo $title; ?></title>
    <link rel="icon" href="https://www.cherrymenu.com/img/faviconcherry.jpg" type="image/gif" sizes="16x16">
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
    </noscript>
    <base href="<?php echo base_url(); ?>">
    <!-- css -->
    <link href="assets/Backend/css/root.css" rel="stylesheet">
    <link href="assets/Backend/css/custome.css" rel="stylesheet">
    <link href="assets/Backend/validation_jquery/css/cmxform.css" rel="stylesheet" type="text/css" />
    <link href="assets/Backend/validation_jquery/css/cmxformTemplate.css" rel="stylesheet" type="text/css" />
    <link href="assets/Backend/validation_jquery/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/Backend/validation_jquery/css/reset.css" rel="stylesheet" type="text/css" />
    <link href="assets/Backend/validation_jquery/css/screen.css" rel="stylesheet" type="text/css" />
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
      <form action="" method="post" id="chnpswdform">
        <div class="top">
          <img src="<?php echo base_url('assets/Backend/img/cerrymenulogo.svg') ?>" alt="icon" class="icon">
          <h1 style="font-size:24px;">Change Password</h1>
        </div>
        <div class="form-area">
          <div class="group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            <i class="fa fa-key"></i>
          </div>
          <div class="group">
            <input type="password" name="con_password" id="con_password" class="form-control" placeholder="Confirm Password">
            <i class="fa fa-key"></i>
          </div>
          <button type="submit" class="btn btn-default btn-block">CHANGE PASSWORD</button>
        </div>
      </form>
      <div class="footer-links row">
        <div class="col-xs-6"><a href="<?php echo base_url('Login'); ?>"><i class="fa fa-sign-in"></i> Login</a></div>
      </div>
    </div>
</body>
</html>
<script type="text/javascript" src="assets/Backend/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#chnpswdform').validate(
        {
            rules:
            {
                password: {
                        required: true,
                        minlength: 5
                    },
                    con_password: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
            },
            messages:
            {
                password: {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 5 characters"
                    },
                    con_password:
                    {
                        required: "Please enter a password",
                        minlength: "Your password must be at least 5 characters",
                        equalTo: "Please enter the same password as above"
                    },
            },
            submitHandler: function (form)
            {
                $('button[type="submit"]').attr('disabled', true);
                form.submit();
            },
        });
    });
</script>