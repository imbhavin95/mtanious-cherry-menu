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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price" class="form-label">Price</label> <span class="require-field">*</span>
                            <div class="input-group">
                                <div class="input-group-addon">AED</i></div>
                                <input type="text" class="form-control" id="price" name="price" tabindex="3" placeholder="Enter Price" value="<?php echo (isset($package) && $package['price']) ? $package['price'] : set_value('price'); ?>">
                            </div>
                            <div id="errorPrice"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="devices_limit" class="form-label">Devices Limit</label> <span class="require-field">*</span>
                        <input type="number" class="form-control" id="devices_limit" name="devices_limit" tabindex="4" placeholder="Enter Devices Limit" value="<?php echo (isset($package) && ($package['devices_limit'] != NULL)) ? $package['devices_limit'] : set_value('devices_limit'); ?>">
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group">
                        <p>Type </p>  -->
                        <!-- <label for="menus" class="form-label">Type</label> <span class="require-field">*</span> -->
                            <!-- <div class="radio radio-info radio-inline">
                                <input type="radio" id="free" value="free" tabindex="3" name="type" >
                                <label for="free"> Free </label>
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="paid" value="paid" tabindex="4" name="type" checked>
                                <label for="paid"> Paid </label>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="users" class="form-label">Users</label> <span class="require-field">*</span>
                        <input type="number" class="form-control" id="users" name="users" tabindex="5" placeholder="Enter Users" value="<?php echo (isset($package) && ($package['users'] != NULL)) ? $package['users'] : set_value('users'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="menus" class="form-label">Menus</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="menus" name="menus" tabindex="6" placeholder="Enter Menus" value="<?php echo (isset($package) && ($package['menus'] != NULL)) ? $package['menus'] : set_value('menus'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categories" class="form-label">Categories</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="categories" name="categories" tabindex="7" placeholder="Enter Categories" value="<?php echo (isset($package) && ($package['categories'] != NULL)) ? $package['categories'] : set_value('categories'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="items" class="form-label">Items</label> <span class="require-field">*</span>
                            <input type="number" class="form-control" id="items" name="items" tabindex="8" placeholder="Enter Items" value="<?php echo (isset($package) && ($package['items'] != NULL)) ? $package['items'] : set_value('items'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="discount" class="form-label">Discount</label> <span class="require-field">*</span>
                            <div class="input-group">
                                <div class="input-group-addon">%</i></div>
                                <input type="number" class="form-control" id="discount" name="discount" tabindex="9" placeholder="Enter Discount" value="<?php echo (isset($package) && ($package['discount'] != NULL)) ? $package['discount'] : set_value('discount'); ?>">
                            </div>
                            <div id="errorDiscount"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date" class="form-label">Period</label> <span class="require-field">*</span>
                            <select type="text" class="form-control" id="duration" name="duration" tabindex="10" placeholder="Enter Date Range"  value="">
                                <option value="">Select</option>
                                <option value="1 month" <?php  if(isset($package['duration'])){ if($package['duration']=="1 month"){ echo "selected";}}?>>1 month</option>
                                <option value="3 months" <?php if(isset($package['duration'])){ if($package['duration']=="3 months"){ echo "selected";}}?>>3 months</option>
                                <option value="6 months" <?php if(isset($package['duration'])){ if($package['duration']=="6 months"){ echo "selected";}}?>>6 months</option>
                                <option value="1 Year" <?php if(isset($package['duration'])){ if($package['duration']=="1 Year"){ echo "selected";}}?>>1 Year</option>
                            </select>
                        </div>
                    </div>

                   <!--  <div class="col-md-6">
                        <div class="form-group">
                            <label for="date" class="form-label">Date</label> <span class="require-field">*</span>
                            <input type="text" class="form-control" id="date" name="date" tabindex="10" placeholder="Enter Date Range" value="<?php echo (isset($package) && $package['start_date'] && $package['end_date'] ) ? date('m/d/Y', strtotime($package['start_date'])).'-'. date('m/d/Y', strtotime($package['end_date'])) : '' ?>">
                        </div>
                    </div> -->
                </div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label> <span class="require-field">*</span>
                            <textarea class="form-control" style="resize: none;" id="description" tabindex="11" name="description" placeholder="Enter Description"><?php echo htmlentities((isset($package) && $package['description']) ? $package['description'] : set_value('description')); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="arabic_description" class="form-label">Arabic Description</label> <span class="require-field">*</span>
                            <textarea class="form-control" style="resize: none;" id="arabic_description" tabindex="11" name="arabic_description" placeholder="Enter Arabic Description"><?php echo htmlentities((isset($package) && $package['arabic_description']) ? $package['arabic_description'] : set_value('arabic_description')); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default" tabindex="5">Submit</button>
                        <a href="<?php echo base_url('admin/packages'); ?>" class="btn btn-square" tabindex="12">Cancel</a>
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
            "price":{required:true,number:true,min:1},
            "devices_limit":{required:true,number:true,digits: true},
            "type":{required:true},
            "users":{required:true,digits: true},
            "menus":{required:true,digits: true},
            "categories":{required:true,digits: true},
            "items":{required:true,digits: true},
            "discount":{required:true,number:true,max:100},
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

    $('#date').daterangepicker(null,function(start, end, label) {
        startDate = start.toISOString();
        endDate = end.toISOString();
        $('#date-error').attr('display','none');
    });
});
</script>