<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1> 
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('restaurant/home/index'); ?>">Dashboard</a></li>
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
            ID Settings
            </div>
                <div class="panel-body">
                    <form class="fieldset-form" action="" method="post" id="backgroundsetting" enctype="multipart/form-data">
                        <fieldset> 
                          <legend><?php echo $label; ?></legend> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                        $required = 'required="required"';
                                        if(isset($settings['bg_after_login']))
                                        {
                                            $required = '';
                                        }
                                    ?>
                                    <label for="backgroundimage" class="form-label">Background Image</label><span class="require-field">*</span>
                                    <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo isset($settings) ? $settings['id'] : '' ?>" />
                                    <input type="file" class="form-control" id="backgroundimage" tabindex="1" name="backgroundimage" <?php echo $required; ?> onchange="readURL(this);" /> <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                                    
                                    <img id="blah" src="<?php
                                    if(isset($settings['bg_after_login']))
                                    {
                                        if (file_exists(DEFAULT_BACKGROUND.$settings['bg_after_login'])) 
                                        {
                                            echo base_url(DEFAULT_BACKGROUND . $settings['bg_after_login']);
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
                                        if(isset($settings['logo']))
                                        {
                                            $required1 = '';
                                        }
                                    ?>
                                    <label for="logo" class="form-label">Logo</label><span class="require-field">*</span>
                                    <input type="file" class="form-control" id="logo" tabindex="2" name="logo" onchange="readLogo(this);" <?php echo $required1; ?> /> <span class="help-block">Accepted formats:  png, jpg , jpeg. Max file size 4 MB</span>
                                    <img id="blah1" src="<?php
                                    if(isset($settings['logo']))
                                    {
                                        if (file_exists(LOGO_IMG.$settings['logo'])) 
                                        {
                                            echo base_url(LOGO_IMG . $settings['logo']);
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
                            <div class="clearfix"></div> 
                            <div class="row" style="display: block;">
                                 <div class="col-md-6">
                                <div class="form-group">
                                    <?php
                                        $required1 = 'required="required"';
                                        if(isset($settings['video']))
                                        {
                                            $required1 = '';
                                        }
                                    ?>
                                    <label for="logo" class="form-label">Video</label><span class="require-field">*</span>
                                    <input type="file" class="form-control" id="video" tabindex="2" name="video"   <?php echo $required1; ?> /> <span class="help-block">Accepted formats:  mp4.</span>
                                    <span class="help-block">Max upload video size: 50mb</span>
                                   <!--  <img id="blah1" src="<?php
                                    if(isset($settings['video']))
                                    {
                                        if (file_exists(LOGO_IMG.$settings['logo'])) 
                                        {
                                            echo base_url(LOGO_IMG . $settings['logo']);
                                        } else 
                                        {   
                                            echo base_url(DEFAULT_IMG);  
                                        }
                                    }else
                                    {
                                        echo base_url(DEFAULT_IMG); 
                                    } ?>" alt="image" width="100" height="100" /> -->

                                    <?php
                                         if(isset($settings['video']) && !empty($settings['video']))
                                        { $videopath=  LOGO_REST .$settings['video'];
                                            ?>
                                    <video controls
                                    class="itemvideo item_img"
                                    src="<?php echo isset($videopath) ? $videopath : '' ; ?>"
                                    width="200" height="200" style="<?php echo isset($videodisplay) ? $videodisplay : '' ; ?>">
                                    Sorry, your browser doesn't support embedded videos, <a href="<?php echo base_url(DEFAULT_IMG); ?>">download it</a> 
                                </video> 
                            <?php } ?>

                                </div>
                            </div> 
                            <div class="col-md-6"></div>
                        </div>
                             <!-- <div class="col-md-6"> -->
                                <div class="form-group col-md-6">
                                    
                                    <label for="rest_name" class="form-label">Restaurant Name</label><span class="require-field">*</span>
                                    <input type="text" class="form-control"  id="rest_name" tabindex="2" name="rest_name" value="<?php echo $rest_name;?>"   required  />  
                                    
                                </div>
                                <div class="form-group col-md-6">
                                     <label for="currency_code" class="form-label">Currency Code</label><span class="require-field">*</span>
            <select type="text" class="form-control" name="currency_code" id="currency_code" tabindex="1"  >
              <option value="">Select Currency Code</option>
              <?php foreach ($currencies as $key => $value) {?>
                <option <?php if($value['code']==$settings['currency']){ echo 'selected';}?> value="<?php echo $value['code'];?>"><?php echo $value['code'];?></option>
              <?php }
              ?>
            </select>
             
          </div> 

                                 
                            <div class="clearfix"></div>
                                 <?php if(isset($rest_name) && !empty($rest_name)){?>
                                 <p><a target='_blank' href="<?php echo 'https://app.cherrymenu.com/'.$rest_name;?>">Web Menu Link</a></p>
                             <?php }?>
                           <!--  </div>  -->
                        <button type="submit" class="btn btn-default">Submit</button>
                        <a href="<?php echo base_url('restaurant/backgroundsetting'); ?>" class="btn btn-square">Cancel</a>
                        </fieldset>
                    </form>
                </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/Backend/validation_jquery/js/jquery.validate.js'); ?>"></script>
<script src="<?php echo base_url('assets/Backend/js/additional-methods.min.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $.validator.addMethod('filesize', function (value, element, param) {
             return this.optional(element)  || (element.files[0].size <= param) 
        }, 
        function(param, element) {
             if(element.files[0].type.includes("image")){
    return 'File size must be less than 4 MB';       
            }else{
    return 'File size must be less than 50 MB';            
            }
}


       /* 'File size must be less than 2 MB',*/
/*
        function (value, element, param) {
             return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than 50 MB'*/

        );
        
        $('#backgroundsetting').validate(
        {
            rules:
            {
                "backgroundimage":{ //2000000
                extension: "png|jpg|jpeg",
                filesize: 4000000 },
                "logo":{
                extension: "png|jpg|jpeg",
                filesize: 4000000 },
                 "currency_code" :{ required :true },
                 "video":{
                extension: "mp4",//50000000
                filesize: 50000000 } 
            },
            messages:
            {
                "backgroundimage":{required:"This field is required"},
                "logo":{required:"This field is required"},
                "currency_code" : {required:"Currency code required"},
                "video" : {required: "This field is required"},
                "filesize": "File size must be less than 50 MB"
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
                $('#blah')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readLogo(input)
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
<?php if($this->session->userdata('login_user')['id'] !="" && !empty($this->session->userdata('login_user')['id'])){
    $sql = "SELECT GREATEST(MAX(IFNULL(m.created_at,0)), MAX(IFNULL(m.updated_at,0)),MAX(IFNULL(u.created_at,0)), MAX(IFNULL(u.updated_at,0)),MAX(IFNULL(s.created_at,0)), MAX(IFNULL(s.updated_at,0))) as latesttimestamp FROM menus m LEFT JOIN users as u on u.restaurant_id = m.restaurant_id LEFT JOIN settings as s on s.user_id = m.restaurant_id WHERE m.restaurant_id = '".$this->session->userdata('login_user')['id']."'";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
  foreach ($query->result() as $row) {?>
     <?php $latesttimestamp = $row->latesttimestamp;?>
<?php }
}
}
?>
<script src="https://www.gstatic.com/firebasejs/5.8.1/firebase.js"></script>
<input type="hidden" name="latesttimestamp" id="latesttimestamp" value="<?php echo $latesttimestamp; ?>">
<script type="text/javascript">
    $( document ).ready(function() {
     //   alert('hello');
      // Initialize Firebase
  var config = {
    apiKey: "AIzaSyB6Y0E1yzKwk00C_ks7YsXwTHkaZJuFSM0",
    authDomain: "cherrymenu-44b6e.firebaseapp.com",
    databaseURL: "https://cherrymenu-44b6e.firebaseio.com",
    projectId: "cherrymenu-44b6e",
    storageBucket: "cherrymenu-44b6e.appspot.com",
    messagingSenderId: "832799477411"
  };
  firebase.initializeApp(config);
        var restaurantid = <?php echo $this->session->userdata('login_user')['id'];?>;
        var latesttimestamp = $("#latesttimestamp").val();
       // alert(restaurantid,latesttimestamp);
        if(restaurantid !="" && latesttimestamp !=""){
            insertData(restaurantid, latesttimestamp);
        }
        insertData(restaurantid, latesttimestamp);
        function insertData(restaurantid, latesttimestamp) {
            firebase.database().ref('Restaurants/' + restaurantid).set({
                LastUpdatedTime: latesttimestamp,
            });          
        }
        });
    </script>