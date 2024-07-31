<div class="page-header">
    <!-- <h1 class="title"><?php //echo $head; ?></h1> -->
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/helptopics/index'); ?>">Help Topics</a></li>
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
        Help Topics
        </div>
            <div class="panel-body">
              <form class="fieldset-form" action="" method="post" id="helptopics" enctype="multipart/form-data">
                <fieldset>
                  <legend><?php echo $label; ?></legend>
                  <div class="form-group">
                    <label for="title" class="form-label">Title</label> <span class="require-field">*</span>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="<?php echo htmlentities((isset($helptopics) && $helptopics['title']) ? $helptopics['title'] : set_value('title')); ?>">
                  </div>
                  <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea placeholder="Enter Description" id="description" name="description"><?php echo htmlentities((isset($helptopics) && $helptopics['description']) ? $helptopics['description'] : set_value('description')); ?> </textarea>
                  </div>
                  <button type="submit" class="btn btn-default">Submit</button>
                  <a href="<?php echo base_url('admin/helptopics'); ?>" class="btn btn-square">Cancel</a>
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
    $('#helptopics').validate(
    {
        rules:
        {
            "title":{required:true,
            normalizer: function(value) {
                return $.trim(value);
            }},
        },
        messages:
        {
            "title":{required:"This field is required"},
        },
        submitHandler: function (form)
        {
            $('button[type="submit"]').attr('disabled', true);
            form.submit();
        },
    });
});
</script>
<script>
    CKEDITOR.replace('description');
</script>