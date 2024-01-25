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
                    <h4 class="page-title"><i class="fa fa-list"></i> &nbsp; Country List</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="row">
                <?php if (check_method_access('countries', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table id="countries_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">Country</th>
                                            <th scope="col">Sort Name</th>
                                            <th scope="col">Phone Code</th>
                                            <th width="100" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (check_method_access('countries', 'add', true)): ?>
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="m-3">
                                <h4 class="text-info mb-0"><i class="fa fa-plus"></i> Add Country</h4>
                                <hr class="my-15">
                                <form method="post" autocomplete="off" id="frm-add-country" onsubmit="return false">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Country Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="country_name" class="form-control"
                                                        id="coun_name" placeholder="Enter country name"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Sort Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="sort_name" class="form-control"
                                                        placeholder="Eg: IN for India" id="s_name"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Slug<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="slug" class="form-control" id="slug"
                                                        placeholder="Eg: india for India"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Phone Code<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="number" name="phone_code" class="form-control"
                                                        placeholder="Eg: 91 for India" id="phone_code"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                            id='btn-save'>
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/edit_country_modal.php');
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/countries.js')?>"></script>