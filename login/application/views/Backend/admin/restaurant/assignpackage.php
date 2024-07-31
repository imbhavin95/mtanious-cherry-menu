<div class="page-header">
    <!-- <h1 class="title"><?php //echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/restaurants/index'); ?>">Restaurants</a></li>
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
        Assign Package
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="assign_packages" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                    <div class="col-md-12 form-group" >
                        <div class="col-md-12">
                            <label for="type" class="form-label">Assign Packages</label> <span class="require-field">*</span>
                            <select class="selectpicker form-control"  id="packages" name="packages[]" multiple>
                                <?php 
                                if(!empty($packages))
                                {
                                    foreach($packages as $row) 
                                    { ?>
                                        <option value="<?php echo htmlentities($row['id']); ?>"><?php echo htmlentities($row['name']); ?></option>
                                    <?php 
                                    } 
                                }
                                ?>
                            </select>
                            <span id="error-type"></span><?php echo form_error('type')?>
                        </div>
                    </div>
               
                <div class="col-md-12">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-default" tabindex="5">Submit</button>
                        <a href="<?php echo base_url('admin/restaurant'); ?>" class="btn btn-square" tabindex="12">Cancel</a>
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
    $('#assign_packages').validate(
    {
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields   
        rules:
        {
            "packages[]":{required:true},
        },
        messages:
        {
            "packages[]":{required:"This field is required"},
        },
        errorPlacement: function (error, element) {
                if(element.attr("name") == "packages[]") {
                    error.appendTo($('#error-type'));
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

});
</script>