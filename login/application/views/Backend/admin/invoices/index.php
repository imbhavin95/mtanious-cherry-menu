<div class="page-header">
    <h1 class="title"><?php echo $head; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/Home'); ?>">Dashboard</a></li>
        <li class="active">Invoices</li>
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
    <!-- View Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">View Invoices</h4>
                    </div>
                    <div id="packagebody">

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Moda Code -->
        <div class="kode-alert kode-alert-icon kode-alert-click alert1" id="package_enable" style="display:none">
            <div id="rest_enable"></div>
        </div>
        <div class="kode-alert kode-alert-icon kode-alert-click alert6" id="package_disable" style="display:none">
            <div id="rest_disable"></div>
        </div>
    
    <div class="panel panel-default">
     
        <div class="panel-body table-responsive">
            <table id="invoices" class="table display drop-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Total Package</th>
                        <th>Total Price</th>
                        <th>Total Discount</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/Backend/validation_jquery/js/jquery.validate.js"></script> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

    $(document).ready(function() {
        $(function () {
            $('#invoices').dataTable({
                autoWidth: false,
                processing: true,
                serverSide: true,
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
                },
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                order: [[1, "desc"]],
                ajax: '<?php echo base_url("admin/invoices/get_details"); ?>',
                columns: [
                    {
                        data: "sr_no",
                        visible: true,
                        sortable: false,
                    },
                    {
                        data: "user_name",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "total_package",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "total_price",
                        visible: true,
                        render: function (data, type, full, meta) {
                            var price = '0.00';
                            if (full.total_price != '' && full.total_price != null) {
                                price = full.total_price;
                            }
                            return 'AED '+price;
                        }
                    },
                    {
                        data: "total_discount",
                        visible: true,
                        render: $.fn.dataTable.render.text()
                    },
                    {
                        data: "is_deleted",
                        visible: true,
                        searchable: false,
                        sortable: false,
                        render: function (data, type, full, meta) {
                            var action = '';
                            console.log(full);
                            var generate_pdf = '<?php echo base_url(); ?>';
                                action +='<div class="btn-group">'
                                action +='<button type="button" class="btn btn-light">Action</button>'
                                action +='<button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'
                                action +='<span class="caret"></span>'
                                action +='<span class="sr-only">Toggle Dropdown</span>'
                                action +='</button>'
                                action +='<ul class="dropdown-menu" role="menu">'
                                action +='<li><a href="'+ generate_pdf +'admin/invoices/generate_invoce/'+ btoa(full.restaurant_id) + '" onclick="return confirm_alert(this)">Generate Invoce</a></li>'
                                action +='</ul>'
                                action +='</div>'
                            return action;
                        }
                    }
                ]
            });
        });
    });

   function confirm_alert(e) {
        swal({
            title: "Are you sure?",
            text: "Generate Invoce!",
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
        }).catch(swal.noop);
        return false;
    }
</script>