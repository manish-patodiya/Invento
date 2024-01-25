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
                    <h4 class="page-title"><i class="fa fa-list"></i> &nbsp; Languages List</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <!-- For Messages -->
            <div class="row">
                <?php if (check_method_access('language', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table id="languages_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Short Name</th>
                                            <th scope="col">Status</th>
                                            <th width="100" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (check_method_access('language', 'add', true)): ?>
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="m-3">
                                <h4 class="text-info mb-0"><i class="fa fa-plus"></i> Add Language</h4>
                                <hr class="my-15">
                                <form method="post" autocomplete="off" id="language_details" onsubmit="return false">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class=" form-group">
                                                <label class="control-label">Language Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class=" controls input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" name="language_name" class="form-control"
                                                        id="language_name" placeholder="Enter language name"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class=" form-group">
                                                <label class="control-label">Short Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class=" controls input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" name="short_name" class="form-control"
                                                        id="short_name" placeholder="Enter short name"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Status<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <select class='form-control ' name="status" id="status"
                                                        data-validation-required-message="This field is required"
                                                        data-live-search="true">
                                                        <option value="">Select Status</option>
                                                        <option value="1">Active</option>
                                                        <option value="0">Unactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                            id="btn-save">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/edit_languages_modal.php');
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/languages.js')?>"></script>