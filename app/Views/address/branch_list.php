<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.btn-lg {
    font-size: 1.286rem;
    padding: 6px 32px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Branches </h4>
                </div>
                <a href="<?php echo base_url("/address") ?>" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-default">
                        <div class="card-body table-responsive">
                            <table id="branch_address_book_table" class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="1">SN</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">GST No</th>
                                        <th scope="col">Phone No</th>
                                        <th width="100" class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="m-3">
                            <form method="post" autocomplete="off" id="branch_address_book_detail"
                                onsubmit="return false">
                                <input type="hidden" value="<?=$address_id?>" name="address_id" id="address_id">
                                <h4 class="text-info mb-0"><i class="fa fa-plus"></i>Add Branch Address</h4>
                                <hr class="my-15">
                                <div class="form-group">
                                    <label for="username" class="control-label">Branch Name<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="name" class="form-control" id="b_name"
                                            placeholder="Enter your name "
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="firstname" class="control-label">Branch Email<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti-email"></i></span>
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Enter your email address"
                                                data-validation-required-message="This field is required" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="lastname" class="control-label">Contact No.<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            <input type="number" name="phone" class="form-control" id="mobile_no"
                                                data-inputmask='"mask": "999-999-9999"' data-mask
                                                placeholder="Enter your phone number"
                                                data-validation-required-message="This field is required" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="control-label">GST No.</label>
                                    <div class="controls">
                                        <input name="gst_no" class="form-control" id="gst_no"
                                            placeholder="Type your Gst no"></input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="control-label">Address<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <textarea name="address" class="form-control" id="address"
                                            placeholder="Type your Address"
                                            data-validation-required-message="This field is required"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-sm pull-right" id="btn-save">
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/edit_view_branch_modal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/address.js')?>"></script>