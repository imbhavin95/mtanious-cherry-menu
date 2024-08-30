<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Packages</li>
    </ol>
</div>
<div class="container-widget">
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
    
        <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="package_enable" style="display:none">
            <div id="rest_enable"></div>
        </div>
        <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="package_disable" style="display:none">
            <div id="rest_disable"></div>
        </div>

    <div class="row billing">
        <!-- Start Today Activity -->
        <div class="col-md-12 col-lg-3">
            <div class="panel panel-widget widget-top">
                <div class="panel-body">
                    <div class="">
                        <p><span>Billing Name : </span><b><?php echo $this->session->userdata('login_user')['name'] ?></b></p>
                        <p><span>Email : </span><b style="text-transform: lowercase;"><?php echo $this->session->userdata('login_user')['email'] ?></b></p>
                    </div>
                </div>
            </div>
            <div class="panel panel-widget widget-button">
            <div class="panel-body">
                <div class="row col-md-12">
                    <div class="col-md-2">
                        <img src="<?php echo base_url()?>assets/Backend/packages/group.svg" alt="img" class="img">
                    </div>
                    <div class="col-md-10">
                        <p>Need help?<a href="https://www.cherrymenu.com/contact" target="_blank" style="color:white">contact us</a></p>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- End Today Activity -->

        <!-- Start Server Status -->
        <div class="col-md-12 col-lg-3">
            <div class="panel panel-widget widget-plans">
                <div class="panel-title">
                    <img src="<?php echo base_url()?>assets/Backend/packages/billingicon.svg" alt="img" class="img"> &nbsp;&nbsp; Active Plan
                </div>
                <div class="panel-body" >
                <?php
                 // echo "<pre>";
                   //print_r($custom_user_plan);
                   //print_r($active_package); 

                   //echo $custom_user_plan[0]->devices_limit; 
                   //echo $active_package[0]->devices_limit; 
                  $flag=0;
                if(!empty($active_package))
                { 
                    foreach ($active_package as $key => $row) 
                    {  
                      if($row->users!=$custom_user_plan[0]->users_limit)
                      {
                        $flag=1;
                      }
                      if($row->menus!=$custom_user_plan[0]->menus_limit)
                      {
                        $flag=1;
                      }
                      if($row->categories!=$custom_user_plan[0]->categories_limit)
                      {
                        $flag=1;
                      }
                      if($row->items!=$custom_user_plan[0]->items_limit)
                      {
                        $flag=1;
                      }
                      if($row->devices_limit!=$custom_user_plan[0]->devices_limit)
                      {
                          $flag=1;
                      }

                    }
                }

                 if(!empty($custom_user_plan) && $flag==1)
                 {
                     foreach ($custom_user_plan as $key => $row) 
                     { ?>
                         <h6>Customized User Plan</h6>
                     <p><span>Users Limit :</span><?php echo $row->users_limit; ?></p>
                     <p><span>Menus Limit :</span><?php echo $row->menus_limit; ?></p>
                    <p><span>Categories Limit :</span><?php echo $row->categories_limit; ?></p>
                    <p><span>Items Limit :</span><?php echo $row->items_limit; ?></p>
                     <p><span>No. of devices :</span><?php echo $row->devices_limit; ?></p> 
                <?php 
                    }  echo "<br>";
                }
                    ?>
                    
                <?php 
                if(!empty($active_package))
                { 
                    foreach ($active_package as $key => $row) 
                    { ?>
                     
                    <h6><?php echo $row->name; ?></h6>
                    <p><span>Users Limit :</span><?php echo $row->users; ?></p>
                    <p><span>Menus Limit :</span><?php echo $row->menus; ?></p>
                    <p><span>Categories Limit :</span><?php echo $row->categories; ?></p>
                    <p><span>Items Limit :</span><?php echo $row->items; ?></p>
                    <p><span>No. of devices :</span><?php echo $row->devices_limit; ?></p>
                    <a class="btn-upgrade" href="<?php echo base_url(); ?>restaurant/packages/list" class="btn btn-default">Upgrade</a>

                <?php 
                    }
                }
                    ?>
                </div>
            </div>
        </div>
        <!-- End Server Status -->

        <!-- Start Profile Widget -->
        <div class="col-md-12 col-lg-6">
            <div class="panel panel-widget widget-plans">
                <div class="panel-title">
                <img src="<?php echo base_url()?>assets/Backend/packages/registry _list.svg" alt="img" class="img"> &nbsp;&nbsp;Invoices
                </div>
                <div class="panel-body">
                <?php 
                    if($package_details)
                    {
                        foreach ($package_details as $key => $row) 
                        {
                    ?>
                        <div class="row  widget-plans-details" >
                            <div class="col-md-9"><?php echo date('d M Y', strtotime($row->updated_at)); ?></div>
                            <div class="col-md-3 download-btn">AED <?php echo $row->price; ?><span><a href="<?php echo base_url() ?>restaurant/packages/invoices/<?php echo base64_encode($row->id);?>"><i class="fa fa-download"></i></a></span></div>
                        </div>
                    <?php
                        }
                    }
                    else
                    {
                        echo "No invoice generated yet";
                    }
                ?>
                </div>
            </div>
        </div>
        <!-- End Profile Widget -->
    </div>
</div>

<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script>

var plan_name = "<?php echo '<b>'.$package[0]['name'].'</b>'; ?>";
    function confirm_alert(e) 
    {
        swal({
            html: "You're about to request an upgrade/subscription to the " + plan_name + ", After confirming, someone from our sales team will get back to you.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then(function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
        });
        return false;
    }
</script>