<div class="page-header">
    <!-- <h1 class="title"><?php //echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/packages/index'); ?>">Packages</a></li>
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
        Package
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="packages" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label> <span class="require-field">*</span>
                                <input type="text" class="form-control" id="name" tabindex="1" name="name" placeholder="Enter Name" value="<?php echo htmlentities((isset($package) && $package['name']) ? $package['name'] : set_value('name')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="arabic_name" class="form-label">Arabic Name</label> <span class="require-field">*</span>
                                <input type="text" class="form-control" id="arabic_name" tabindex="2" name="arabic_name" placeholder="Enter Arabic Name" value="<?php echo (isset($package) && $package['arabic_name']) ? $package['arabic_name'] : set_value('arabic_name'); ?>">
                            </div>
                        </div>
                  </div>
                 
                  
                <div class="col-md-12">
                <div class="col-md-4">
                        <div class="form-group">
                        <label for="devices_limit" class="form-label">Devices Limit</label> <span class="require-field">*</span>
                        <input type="number" class="form-control" id="devices_limit" name="devices_limit" tabindex="3" placeholder="Enter Devices Limit" value="<?php echo (isset($package) && ($package['devices_limit'] != NULL)) ? $package['devices_limit'] : set_value('devices_limit'); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <label for="users" class="form-label">Users</label> <span class="require-field">*</span>
                        <input type="number" class="form-control" id="users" name="users" tabindex="4" placeholder="Enter Users" value="<?php echo (isset($package) && ($package['users'] != NULL)) ? $package['users'] : set_value('users'); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="menus" class="form-label">Menus</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="menus" name="menus" tabindex="5" placeholder="Enter Menus" value="<?php echo (isset($package) && ($package['menus'] != NULL)) ? $package['menus'] : set_value('menus'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categories" class="form-label">Categories</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="categories" name="categories" tabindex="6" placeholder="Enter Categories" value="<?php echo (isset($package) && ($package['categories'] != NULL)) ? $package['categories'] : set_value('categories'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="items" class="form-label">Items</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="items" name="items" tabindex="7" placeholder="Enter Items" value="<?php echo (isset($package) && ($package['items'] != NULL)) ? $package['items'] : set_value('items'); ?>">
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-12">
                   
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="date" class="form-label">Date</label> <span class="require-field">*</span>
                            <input type="text" class="form-control" id="date" name="date" tabindex="10" placeholder="Enter End Date" value="<?php// echo (isset($package) && $package['start_date'] && $package['end_date'] ) ? date('m/d/Y', strtotime($package['start_date'])).'-'. date('m/d/Y', strtotime($package['end_date'])) : '' ?>">
                        </div>
                    </div>
                </div> -->
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label> <span class="require-field">*</span>
                            <textarea class="form-control" style="resize: none;" id="description" tabindex="8" name="description" placeholder="Enter Description"><?php echo htmlentities((isset($package) && $package['description']) ? $package['description'] : set_value('description')); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="arabic_description" class="form-label">Arabic Description</label> <span class="require-field">*</span>
                            <textarea class="form-control" style="resize: none;" id="arabic_description" tabindex="9" name="arabic_description" placeholder="Enter Arabic Description"><?php echo htmlentities((isset($package) && $package['arabic_description']) ? $package['arabic_description'] : set_value('arabic_description')); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default" tabindex="10">Submit</button>
                        <a href="<?php echo base_url('admin/packages'); ?>" class="btn btn-square" tabindex="11">Cancel</a>
                    <div>
                </div>
                </fieldset>
              </form>
            </div>
      </div>
    </div>
</div>
<script type="text/javascript" src="assets/Backend/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    var startdate = '<?php echo (isset($package) && $package['start_date'] && $package['end_date'] ) ? date('m/d/Y', strtotime($package['start_date'])) : '' ?>';
    var enddate = '<?php echo (isset($package) && $package['end_date'] ) ? date('m/d/Y', strtotime($package['end_date'])) : '' ?>';
    (function($) {
        $.fn.numeric = function(options) {
            return this.each(function() {
                var $this = $(this);
                $this.keypress(options, function(e) {
                    // allow backspace and delete
                    if (e.which == 8 || e.which == 0) {
                        return true;
                    }
                    // allow float
                    if (e.which == 46 && this.value.length >=1 && this.value.indexOf('.') == -1) {
                        return true;
                    }
                    //if the letter is not digit
                    if (e.which < 48 || e.which > 57) return false;
                    // check max range
                    var dest = e.which - 48;
                    var result = this.value + dest.toString();
                    if (result > e.data.max) {
                        return false;
                    }
                });
            });
        };
    })(jQuery);

    $('#price').numeric({
            max: 999999
    });

    $('#packages').validate(
    {
        rules:
        {
            "name":{required:true,
            normalizer: function(value) {
                return $.trim(value);
            }},
            "arabic_name":{required:true,
            normalizer: function(value) {
                return $.trim(value);
            }},
            "devices_limit":{required:true,digits: true},
            "users":{required:true,digits: true},
            "menus":{required:true,digits: true},
            "categories":{required:true,digits: true},
            "items":{required:true,digits: true},
            "date":{required:true},
            "description":{required:true},
            "arabic_description":{required:true},
        },
        messages:
        {
            "name":{required:"This field is required"},
        },
        errorPlacement: function (error, element) {
            if(element.attr("name") == "price") {
                error.appendTo($('#errorPrice'));
            }else if(element.attr("name") == "discount") {
                error.appendTo($('#errorDiscount'));
            }else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form)
        {
            $('button[type="submit"]').attr('disabled', true);
            form.submit();
        },
    });

    $('#date').daterangepicker({startDate: startdate,endDate: enddate},function(start, end, label) {
        $('#date-error').attr('display','none');
    });
});
</script>