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
                    <h4 class="page-title"><i class="fa fa-list"></i> Unit of Measure</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <?php if (check_method_access('unit', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>
                        <div class="card card-default">
                            <div class="card-body table-responsive">
                                <table id="unit_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Base Unit</th>
                                            <th scope="col">Conversion Rate</th>
                                            <th width="100" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (check_method_access('unit', 'add', true)): ?>
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="m-3">
                                <form method="post" autocomplete="off" id="unit_detail" onsubmit="return false">
                                    <h4 class="mb-0"><i class="fa fa-plus"></i> Add Unit</h4>
                                    <hr class="my-15">
                                    <div class="form-group">
                                        <label for="username" class="control-label">Title<span
                                                class='mandatory'>*</span></label>
                                        <div class="controls">
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Enter your title "
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname" class="control-label">Base Unit</label>
                                        <div class="">
                                            <select name="base_unit" class="form-control" value="0" id="base_unit">
                                                <option value="">Select base unit</option>
                                                <?php foreach ($unit as $v) {?>
                                                <option value="<?=$v->id?>"><?=$v->title?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="lastname" class="control-label">Conversion Rate<span
                                                class='mandatory'>*</span></label>
                                        <div class="controls">
                                            <input type="number" name="con_rate" step="0.01" class="form-control"
                                                id="con_rate" placeholder="Enter your conversion rate"
                                                data-validation-required-message="This field is required" />
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
                    <?php endif;?>
                </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/edit_unit_modal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/unit.js')?>"></script>