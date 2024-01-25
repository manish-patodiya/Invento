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
<?php if (check_method_access('address', 'view', true)) {?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Address Book List</h4>
                </div>

            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <?php if (check_method_access('address', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>
                        <div class="card card-default">
                            <div class="card-body table-responsive">
                                <table id="address_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col" width="1">SN</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
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
                        <?php if (check_method_access('address', 'add', true)): ?>
                        <div class="card card-default">
                            <div class="m-3">
                                <form method="post" autocomplete="off" id="address_detail" onsubmit="return false">
                                    <h4 class="mb-0 text-info"><i class="fa fa-plus"></i> Add Address</h4>
                                    <hr class="my-15">
                                    <div class="form-group">
                                        <label for="username" class="control-label">Company Name<span
                                                class='mandatory'>*</span></label>
                                        <div class="controls">
                                            <input type="text" name="name" class="form-control" id="title"
                                                placeholder="Enter your name "
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname" class="control-label">Company Email<span
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
                                        <label for="lastname" class="control-label">Website (URL)</label>
                                        <div class="controls">
                                            <input name="website_url" class="form-control" id="website_url"
                                                placeholder="Type your Website URL"></input>
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
                        <?php endif;?>
                    </div>
                </div>
        </section>
    </div>
</div>
<?php
}
echo view('sub_modals/edit_address_modal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/address.js')?>"></script>