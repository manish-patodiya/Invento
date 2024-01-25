<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<div class="alert alert-success" style="display:none" id="success-msg"></div>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; HSN Details</h4>
                </div>
                <?php if (check_method_access('hsn', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="#" class="btn btn-info btn-sm add"><i class="fa fa-plus"></i>
                        Add HSN</a>
                    <a href="#" class='btn btn-sm btn-success' data-bs-toggle="modal"
                        data-bs-target="#mdl-bulk-upload-hsns"><i class="fa fa-plus"></i>
                        Bulk Upload</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <!-- For Messages -->

            <div class="card">
                <div class="card-body table-responsive">
                    <table id="hsn_details_table" class="table" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">HSN Code</th>
                                <th scope="col">HSN Code (4 digits)</th>
                                <th scope="col">Details</th>
                                <th scope="col">GST Rate</th>
                                <th width="100" class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/add_hsn_modal.php');
echo view('sub_modals/edit_hsn_modal.php');
echo view('sub_modals/bulk_upload_hsn.php');
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/hsn.js')?>"></script>