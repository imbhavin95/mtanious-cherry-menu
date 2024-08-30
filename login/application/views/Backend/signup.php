<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <title>Get Registered with Cherrymenu Now!</title>

  <meta name="description" content="Get Registered with Cherrymenu to procure an ocean of opportunities proffered by intriguing digital menu boards.">
  <meta name="keywords" content="bootstrap, admin, dashboard, flat admin template, responsive," />
  
  <link rel="icon" href="https://www.cherrymenu.com/img/faviconcherry.jpg" type="image/gif" sizes="16x16">
    <!-- <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
    </noscript>  -->
  <base href="<?php echo base_url(); ?>">
  
  <!-- ========== Css Files ========== -->
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
        html {
            height: 100%
                    }
        [type=file] {
            position: absolute;
            filter: alpha(opacity=0);
            opacity: 0;
        }
        input,
        [type=file] + label {
          border: 1px solid #CCC;
          border-radius: 3px;
          text-align: left;
          padding: 10px;
          margin: 0;
          left: 0;
          position: relative;
        }
        [type=file] + label {
          cursor: pointer;
        }
        [type=file] + label:hover {
          background: #3399ff;
        }
        label {
        margin-bottom: 5px;
        font-weight: unset;
      }
    </style> 
  </head>
  <body>
     <div class="login-form">
      <form action="" method="post" enctype="multipart/form-data" id="signup">
        <div class="top">
            <img src="<?php echo base_url('assets/Backend/img/cerrymenulogo.svg') ?>" alt="icon" class="icon">
            <h2 class="register-title">Register</h2>
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
            <input type="text" class="form-control" name="name" id="name" tabindex="1" placeholder="Name" maxlength="25">
            <i class="fa fa-user"></i>
          </div>
          <div class="group">
            <input type="text" class="form-control" name="email" id="email" tabindex="3" placeholder="E-mail">
            <i class="fa fa-envelope-o"></i>
          </div>

            <div class="group">
            <input type="text" class="form-control" name="phone" id="phone" tabindex="3" placeholder="Phone Number">
            <i class="fa fa-phone"></i>
           </div>
             <div class="group">
            <select type="text" class="form-control" name="currency_code" id="currency_code" tabindex="1"  >
              <option value="">Select Currency Code</option>
              <?php foreach ($currencies as $key => $value) {?>
                <option value="<?php echo $value['code'];?>"><?php echo $value['code'];?></option>
              <?php }
              ?>
            </select>
            <i class="fa fa-user"></i>
          </div> 
          <div class="group">
            <input type="password" class="form-control" name="password" id="password" tabindex="4" placeholder="Password">
            <i class="fa fa-key"></i>
          </div>
          <div class="group">
            <input type="password" class="form-control" name="confirm_password" id="confirm_password" tabindex="5" placeholder="Password again">
            <i class="fa fa-key"></i>
          </div>
            <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6LdVIx0qAAAAAGirSHv8bZYsl1NC4nxNanR7S385"></div>
            <input name="recaptcha_validate" type="hidden" id="recaptcha_validate" value="">
              <button type="submit" class="btn btn-default btn-block" style="margin-top: 20px;" id="signupBtn" tabindex="6">REGISTER NOW</button>
            </div>
      </form>
      <div class="footer-links row">
        <div class="col-xs-6"><a href="<?php echo base_url('Login'); ?>" style="color:white"><i class="fa fa-sign-in "></i> Login</a></div>
      </div>
    </div>
</body>
</html>
<script type="text/javascript" src="assets/Backend/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script src="assets/Backend/js/additional-methods.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
$(document).ready(function(){

  jQuery.extend(jQuery.validator.messages, {
    maxlength: jQuery.validator.format("Please enter characters less than {0} characters."),
    noHTML: jQuery.validator.format("Special characters are not allowed")
  });


  jQuery.validator.addMethod("noHTML", function(value, element) {
    // return true - means the field passed validation
    // return false - means the field failed validation and it triggers the error
    return this.optional(element) || /^([a-zA-Z0-9 ]+)$/.test(value);
}, "Special characters are not allowed");

  $("#signupBtn").on('click', function (e){
      if(grecaptcha.getResponse() !== "") {
          $("#recaptcha_validate").val('1');
      }
  })

  $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 2 MB');
    $('#signup').validate(
    {
        ignore: [],
        rules:
        {
         "name":{required:true, maxlength:25,noHTML:true },
         "email":{required:true,email:true,
              remote: {
                    url: '<?php echo base_url('registration/checkUniqueEmail'); ?>',
                    type: "post",
                    data: {
                        email: function () {
                            return $("#email").val();
                        }
                    },
                }},
                "phone" : { required: true },
         "currency_code" :{ required :true },
         "password":{required:true, minlength : 6},
         "confirm_password":{required:true, minlength : 6,equalTo : "#password"},
            "recaptcha_validate" : {required: true}
        },
        messages:
        {
            "name":{required:"This field is required"},
            "email":{required:"This field is required",email:"Please enter a valid E-mail",remote: "The email is already registered."},
            "currency_code" : {required:"Currency code required"},
            "password":{required:"This field is required"},
            "recaptcha_validate": {required: "Please complete the reCAPTCHA"}
        },
        errorPlacement: function (error, element) {
            if(element.attr("name") == "image") {
                error.appendTo($('#imgerror'));
            }else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form)
        {
            $('button[type="submit"]').attr('disabled', true);
            $('.alert').hide();
            form.submit();
        },
    });
});


$("[type=file]").on("change", function(){
  // Name of file and placeholder
  var file = this.files[0].name;
  var dflt = $(this).attr("placeholder");
  if($(this).val()!=""){
    $(this).next().text(file);
  } else {
    $(this).next().text(dflt);
  }
});
setTimeout(function(){ $('.alert').hide() }, 2000);
</script> 