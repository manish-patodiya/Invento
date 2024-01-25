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
                    <h4 class="page-title"><i class="fa fa-list"></i> &nbsp; Cities List</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="row">
                <?php if (check_method_access('city', 'add', true)) {?>
                <div class="col-md-8">
                    <?php } else {?>
                    <div class="col-md-12">
                        <?php }?>

                        <div class="card card-default">
                            <div class="card-body table-responsive">
                                <table id="cities_table" class="table" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">SN</th>
                                            <th scope="col">City</th>
                                            <th scope="col">State</th>
                                            <!-- <th scope="col">Status</th> -->
                                            <th width="100" class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <!-- <table class="table table-striped" id="table-relations">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table> -->
                            </div>
                        </div>
                    </div>
                    <?php if (check_method_access('city', 'add', true)): ?>
                    <div class="col-md-4">
                        <div class="card card-default">
                            <div class="m-3">
                                <h4 class="text-info mb-0"><i class="fa fa-plus"></i> Add City</h4>
                                <hr class="my-15">
                                <form method="post" autocomplete="off" id="city_details" onsubmit="return false">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class=" form-group">
                                                <label class="control-label">City Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class=" controls input-group">
                                                    <div class="input-group-prepend">
                                                    </div>
                                                    <input type="text" name="city_name" class="form-control"
                                                        id="city_name" placeholder="Enter city name"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="username" class="control-label">State<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <select class='form-control ' name="state_id" id="state"
                                                        data-validation-required-message="This field is required"
                                                        data-live-search="true">
                                                        <option value="">Select state</option>
                                                        <?php foreach ($state as $key => $value) {?>
                                                        <option value="<?=$value->state_id?>"><?=$value->state_name?>
                                                        </option>
                                                        <?php }?>
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
                </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/edit_cities_modal.php');
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/cities.js')?>"></script>