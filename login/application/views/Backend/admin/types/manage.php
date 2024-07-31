<div class="page-header">
    <!-- <h1 class="title"><?php //echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/types/index'); ?>">Types</a></li>
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
        Type
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="types" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="form-group">
                    <label for="type" class="form-label">Type</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="type" name="type" placeholder="Enter Type" value="<?php echo htmlentities((isset($type) && $type['type']) ? $type['type'] : set_value('type')); ?>">
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('admin/types'); ?>" class="btn btn-square">Cancel</a>
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
    $('#types').validate(
    {
        rules:
        {
            "type":{required:true,
                normalizer: function(value) {
                return $.trim(value);
            },
            remote: 
                {
                    url: '<?php echo base_url('admin/types/checkUniqueType'); ?>',
                    type: "post",
                    data: {
                        type: function () {
                            return $("#type").val();
                        },
                        type_id: '<?php echo isset($type['id']) ? base64_encode($type['id']) : "" ?>',
                    },
                }},
        },
        messages:
        {
            "type":{required:"This field is required"},
        },
        submitHandler: function (form)
        {
            $('button[type="submit"]').attr('disabled', true);
            form.submit();
        },
    });
});
</script>
