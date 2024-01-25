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
                    <h4 class="page-title"><i class="fa fa-list"></i> Category List</h4>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="alert alert-success" style="display:none" id="success-msg"></div>
            <!-- mein body -->
            <div class="row">
                <?php if (check_method_access('categories', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>
                        <div class="card card-default">
                            <div class="card-body table-responsive">
                                <table id="categories_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">Category Name</th>
                                            <th width="100" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (check_method_access('categories', 'add', true)): ?>
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="m-3">
                                <form method="post" autocomplete="off" id="categories_detail" onsubmit="return false">
                                    <h4 class="mb-0"><i class="fa fa-plus"></i> Add Category</h4>
                                    <hr class="my-15">
                                    <div class="form-group">
                                        <label for="username" class="control-label">Category Name<span
                                                class='mandatory'>*</span></label>
                                        <div class="controls">
                                            <input type="text" name="category_name" class="form-control" id="ctegory"
                                                placeholder="Enter your category name"
                                                data-validation-required-message="This field is required">
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
                        <div class="card card-default">
                            <div class="m-3">
                                <p class="mb-0 ">
                                    Please make sure the csv file is UTF-8 encoded and not saved with byte order mark
                                    (BOM).
                                </p>
                                <div class='alert alert-danger' style='display:none;' id='bulk-upld-errors'></div>
                                <a class="btn btn-success btn-sm pull-right" id="btn-add-csv">Import CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/product_base/edit_category_modal.php');
echo view('sub_modals/product_base/upload_csv_file.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/category.js')?>"></script>