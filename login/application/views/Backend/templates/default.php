<!DOCTYPE html>
<html lang="en">
<head>

    <?php $this->load->view('Backend/templates/header')?>
    <script type="text/javascript" src="assets/Backend/js/jquery.min.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/bootstrap/bootstrap.min.js"></script>
    <style type="text/css">
    .icons-design{
    padding: 6px;
    border: 1px solid #aaa;
    border-radius: 6px;
    background: #ffffff;
    }
    .icons-design img{
        width: 18px;
    }
</style>
</head>
<body>
<?php $action = $this->router->fetch_method(); ?>
    <?php $userType = ($this->session->userdata('login_user')['role'] === ADMIN) ? ADMIN : RESTAURANT; ?>
    <!-- page loading -->
    <div class="loading"><img src="https://www.cherrymenu.com/login/assets/Backend/img/loading.gif" alt="loading-img"></div>
    <!-- START TOP -->
    <div id="top" class="clearfix">
        <div class="applogo" style=" padding: 2px 14px 0px 0px;margin-top: 0px;">
            <a href="<?php echo base_url(); ?>" class="logo"><img src="<?php echo base_url('assets/Backend/img/white-03.svg') ?>" style="height: 51px; padding: 14px 15px 0px 0px;margin-top: -11px;"/></a>
        </div>
        <a href="javascript:void(0);" class="sidebar-open-button"><i class="fa fa-bars"></i></a>
        <a href="javascript:void(0);" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i></a>
        <ul class="top-right">
            <li class="dropdown link">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox">
            <?php
                if ($this->session->userdata('login_user')['image'] != '') 
                {
                    if(is_sub_admin())
                    {
                        $path = RESTAURANT_IMAGES. '/' . $this->session->userdata('login_user')['restaurant_id']. '/users/' . $this->session->userdata('login_user')['id'] . '/'.$this->session->userdata('login_user')['image'];
                    }
                    else
                    {
                        $path = RESTAURANT_IMAGES . '/'.$this->session->userdata('login_user')['id'].'/'. $this->session->userdata('login_user')['image'];
                    }
                    if (CheckImageType($path)) 
                    {
                        ?>
                            <img src="<?php echo base_url($path) ?>" alt="image">
                        <?php
                    } else 
                    {   ?>
                            <img src="<?php echo base_url(DEFAULT_USER_IMG) ?>" alt="">
                        <?php 
                    }
                } else 
                {   ?>
                   <img src="<?php echo base_url(DEFAULT_USER_IMG) ?>" alt="">
                    <?php 
                } 
            ?><b>
                <?php echo htmlentities($this->session->userdata('login_user')['name']); ?></b><span class="caret"></span></a>
                <ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
                    <li><a href="<?php echo base_url($userType.'/Home/profile'); ?>"><i class="fa falist fa-wrench"></i> Profile Setting</a></li>
                    <?php if (is_restaurant()) {?>
                        <li><a href="<?php echo base_url($userType.'/packages'); ?>"><i class="fa falist fa-dropbox"></i>Billing and Plans</a></li>
                    <!-- <li><a href="<?php echo base_url($userType.'/packages'); ?>" class="<?php echo ($this->controller == 'packages' || $this->controller == 'Packages') ? 'active' : ''; ?>"><i class="fa fa-dropbox"></i>Billing and Plans</a></li> -->
                    <?php } ?>
                    <li><a href="<?php echo base_url('Login/logout'); ?>"><i class="fa falist fa-power-off"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="sidebar clearfix">
        <ul class="sidebar-panel nav">
            <?php if(!is_sub_admin()){ ?>
                <li><a href="<?php echo base_url($userType.'/Home'); ?>"  class="<?php echo ($this->controller == 'Home' && $action != 'profile') ? 'active' : ''; ?>"><span class="icon color5"><i class="fa fa-home"></i></span>Dashboard</a></li>
            <?php } ?>
            <?php if (is_admin()) {?>
            <li><a href="<?php echo base_url($userType.'/restaurant'); ?>" class="<?php echo ($this->controller == 'restaurant') ? 'active' : ''; ?>"><span class="icon color6"><i class="fa fa-cutlery"></i></span>Restaurants</a></li>
            <li><a href="<?php echo base_url($userType.'/packagerequest'); ?>" class="<?php echo ($this->controller == 'packagerequest' || $this->controller == 'packagerequest') ? 'active' : ''; ?>"><span class="icon color6"><i class="fa fa-check-square"></i></span>Upgrade Package Request</a></li>
            <li><a href="<?php echo base_url($userType.'/types'); ?>" class="<?php echo ($this->controller == 'types') ? 'active' : ''; ?>"><span class="icon color6"><i class="fa fa-bars"></i></span>Types</a></li>
            <li><a href="<?php echo base_url($userType.'/packages'); ?>"  class="<?php echo ($this->controller == 'packages' || $this->controller == 'Packages') ? 'active' : ''; ?>"><span class="icon color10"><i class="fa fa-dropbox"></i></span>Packages </a></li>
            
            <li><a href="<?php echo base_url($userType.'/activetablets'); ?>" class="<?php echo ($this->controller == 'activetablets' || $this->controller == 'Activetablets') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-tablet"></i></span>Active Tablets</a></li>
            <!-- <li><a href="<?php echo base_url($userType.'/packages'); ?>" class="<?php echo ($this->controller == 'packages') ? 'active' : ''; ?>"><span class="icon color6"><i class="fa fa-dropbox"></i></span>Packages</a></li>
            <li><a href="<?php echo base_url($userType.'/packagerequest'); ?>" class="<?php echo ($this->controller == 'packagerequest') ? 'active' : ''; ?>"><span class="icon color6"><i class="fa fa-th-list"></i></span>Package Request</a></li> -->
            <?php }?>

            <?php if (is_restaurant()) {?>
                <li><a href="<?php echo base_url($userType.'/users'); ?>" class="<?php echo ($this->controller == 'users') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-users"></i></span>Users</a></li>
              
            <?php } ?>

            <?php if (is_restaurant()) {
             if($this->session->userdata('login_user')['order_feature'] == 1){?>
                <li><a href="javascript:void(0);" class="<?php echo ($this->controller == 'orders') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-shopping-cart"></i></span>Orders<span class="caret"></span></a>
                <ul <?php echo ($this->controller == 'orders') ? 'style="display:block"' : ''; ?>>
                    <li><a href="<?php echo base_url($userType.'/orders'); ?>"  class="<?php echo ($this->controller == 'orders') ? 'active' : ''; ?>">Order List</a></li>
                    <li><a href="<?php echo base_url($userType.'/orders/orderreport'); ?>"  class="<?php echo ($this->controller == 'orders' || $this->controller == 'orderreport') ? 'active' : ''; ?>">Item reports</a></li>
                </ul>
                </li>
            <?php } }?>

             <?php if (is_restaurant() || is_sub_admin()) {?>
                <li><a href="<?php echo base_url($userType.'/menus'); ?>" class="<?php echo ($this->controller == 'menus') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-list-ul"></i></span>Menus</a></li>
                <!--  <li><a href="<?php echo base_url($userType.'/menus_default'); ?>" class="<?php echo ($this->controller == 'menus') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-list-ul"></i></span>Default Menus</a></li> -->
                <li><a href="<?php echo base_url($userType.'/categories'); ?>" class="<?php echo ($this->controller == 'categories') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-list-ul"></i></span>Categories</a></li>
                <li><a href="<?php echo base_url($userType.'/items'); ?>" class="<?php echo ($this->controller == 'items' || $this->controller == 'ItemImages') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-list-ul"></i></span>Items</a></li>
            <?php }?>

            <?php if (is_restaurant()) {?>
            <!-- <li><a href="<?php echo base_url($userType.'/packages'); ?>" class="<?php echo ($this->controller == 'packages' || $this->controller == 'Packages') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-dropbox"></i></span>Billing and Plans</a></li> -->
            <li><a href="<?php echo base_url($userType.'/activetablets'); ?>" class="<?php echo ($this->controller == 'activetablets' || $this->controller == 'Activetablets') ? 'active' : ''; ?>"><span class="icon color8"><i class="fa fa-tablet"></i></span>Active Devices</a></li>
            <li>
                <a href="javascript:void(0);" class="<?php echo ($this->controller == 'feedbacks' || $this->controller == 'reports') ? 'active' : ''; ?>"><span class="icon color10"><i class="fa fa-bar-chart"></i></span>Reports<span class="caret"></span></a>
                <ul <?php echo ($this->controller == 'feedbacks' || $this->controller == 'reports') ? 'style="display:block"' : ''; ?>>
                    <li><a href="<?php echo base_url($userType.'/feedbacks'); ?>"  class="<?php echo ($this->controller == 'feedbacks') ? 'active' : ''; ?>">Feedback entries </a></li>
                    <li><a href="<?php echo base_url($userType.'/reports'); ?>" class="<?php echo ($this->controller == 'reports' && $action == 'index') ? 'active' : ''; ?>">Item Clicks & usage reports</a></li>
                    <li><a href="<?php echo base_url($userType.'/reports/category_clicks'); ?>" class="<?php echo ($this->controller == 'reports' && $action == 'category_clicks') ? 'active' : ''; ?>">Category Clicks & usage reports</a></li>
                </ul>
            </li>
            <?php } ?>
            
            <?php if(!is_sub_admin()){?>
            <li>
                <a href="javascript:void(0);" class="<?php echo ($this->controller == 'helptopics' || $this->controller == 'Helptopics' || $this->controller == 'backgroundsetting' || ($this->controller == 'Home' && $action == 'profile')) ? 'active' : ''; ?>"><span class="icon color10"><i class="fa fa-cog"></i></span>Settings<span class="caret"></span></a>
                <ul <?php echo ($this->controller == 'helptopics' || $this->controller == 'Helptopics' || $this->controller == 'backgroundsetting' || ($this->controller == 'Home' && $action == 'profile')) ? 'style="display:block"' : ''; ?>>
                    <li><a href="<?php echo base_url($userType.'/idsettings'); ?>"  class="<?php echo ($this->controller == 'backgroundsetting') ? 'active' : ''; ?>">Id Settings</a></li>
                <?php if($userType === ADMIN) { ?>
                    <li><a href="<?php echo base_url($userType.'/helptopics'); ?>" class="<?php echo ($this->controller == 'helptopics' || $this->controller == 'Helptopics') ? 'active' : ''; ?>">Help Topics</a></li>
                <?php } ?>
                    <li><a href="<?php echo base_url($userType.'/Home/profile'); ?>" class="<?php echo (($this->controller == 'Home' && $action == 'profile') || ($this->controller == 'home' && $action == 'profile')) ? 'active' : ''; ?>">Profile Setting</a></li>
                </ul>
            </li>
                <?php } ?>
        </ul>
    </div>
    <div class="content">
       <?php echo $content; ?>
        <?php $this->load->view('Backend/templates/footer')?>
    </div>
</body>
</html>
    <!-- ================================================
    Plugin.js - Some Specific JS codes for Plugin Settings
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/plugins.js"></script>
    <!-- ================================================
    Bootstrap Select
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/bootstrap-select/bootstrap-select.js"></script>
    <!-- ================================================
    Bootstrap Toggle
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <!-- ================================================
    Bootstrap WYSIHTML5
    ================================================ -->
    <!-- main file -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/bootstrap-wysihtml5/wysihtml5-0.3.0.min.js"></script>
    <!-- bootstrap file -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>

    <!-- ================================================
    Summernote
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/summernote/summernote.min.js"></script>

    <!-- ================================================
    Rickshaw
    ================================================ -->
    <!-- ================================================
    Data Tables
    ================================================ -->
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/datatables.min.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/dataTables.buttons.min.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/jszip.min.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/pdfmake.min.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/vfs_fonts.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/datatables/buttons.html5.min.js"></script>

    <!-- ================================================
    Sweet Alert
    ================================================ -->
    <!-- <script src="https://www.cherrymenu.com/login/assets/Backend/js/sweet-alert/sweet-alert.min.js"></script> -->
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/sweetalert2.all.min.js"></script>

    <!-- ================================================
    Kode Alert
    ================================================ -->
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/kode-alert/main.js"></script>
    <!-- ================================================
    jQuery UI
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/jquery-ui/jquery-ui.min.js"></script>
    <!-- ================================================
    Moment.js
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/moment/moment.min.js"></script>
    <!-- ================================================
    Bootstrap Date Range Picker
    ================================================ -->
    <script type="text/javascript" src="https://www.cherrymenu.com/login/assets/Backend/js/date-range-picker/daterangepicker.js"></script>
    <script src="https://www.cherrymenu.com/login/assets/Backend/js/jquery.fancybox.min.js"></script>
   <script>
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });
        setTimeout(function(){ $('.alert').hide() }, 3000);
        
    </script>
