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
                    <h4 class="page-title"><i class="fa fa-list"></i> Update Staff Info</h4>
                </div>
                <a href="<?php echo base_url("/staffmanager") ?>" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>

            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <form method="post" autocomplete="off" id="edit-staff-detail" onsubmit="return false">
                            <div class="m-3 row">
                                <input type="hidden" id='staff-id' value='<?=base64_encode($details['id'])?>'>
                                <input type="hidden" name='user_id' id='' value='<?=$details['user_id']?>'>
                                <h4 class="text-info  col-md-4">Staff Manager Info</h4>
                                <div class="page-title col-md-4">
                                    <select name="role_id" class='form-control' id="staff">
                                        <option value="">Select Staff Role</option>
                                        <?php foreach ($roles as $v) {$array = [7, 8];?>
                                        <?php if (in_array("$v->id", $array)) {?>
                                        <option value="<?=$v->id?>"
                                            <?=$v->id == $details['role_id'] ? "selected" : false?>>
                                            <?=$v->role?>
                                        </option>
                                        <?php }}?>
                                        <?php?>
                                    </select>
                                </div>
                                <hr class="my-15">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstname" class="control-label">First Name<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" value='<?=$details['first_name']?>' name="firstname"
                                                    class="form-control" id="firstname"
                                                    placeholder="Enter your firstname"
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastname" class="control-label">Last Name<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" value='<?=$details['last_name']?>' name="lastname"
                                                    class="form-control" id="lastname" placeholder="Enter your lastname"
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile_no">Mobile No<span class='mandatory'>*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                                <input type="number" name="mobile_no" class="form-control"
                                                    id="mobile_no" data-inputmask='"mask": "999-999-9999"' data-mask
                                                    placeholder="Enter your mobile number"
                                                    value='<?=$details['phone']?>'
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class=" form-group">
                                            <label for="email" class="control-label">Email<span
                                                    class='mandatory'>*</span></label>
                                            <div class=" input-group">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"><i class="ti-email"></i></span>
                                                    <input type="email" name="email" class="form-control" id="email"
                                                        placeholder="Enter your email address"
                                                        value='<?=$details['email']?>'
                                                        data-validation-required-message="This field is required" />
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" row col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="control-label">Address<span
                                                class='mandatory'>*</span></label>
                                        <div class="controls">
                                            <textarea name="address" class="form-control" id="address"
                                                placeholder="Type your address" value=''
                                                data-validation-required-message="This field is required"><?=$details['address']?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="companyname" class="control-label">State<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="state" class="form-control" id='staff-state'
                                                    data-validation-required-message="This field is required">
                                                    <option value="">Select state</option>
                                                    <?php foreach ($state as $v) {?>
                                                    <option value="<?=$v->state_id?>"
                                                        <?=$v->state_id == $details['state_id'] ? 'selected' : false?>>
                                                        <?=$v->state_name?></option>
                                                    <?php }?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">City<span class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="citie" class="form-control" id='staff-citie'
                                                    data-validation-required-message="This field is required">
                                                    <?php foreach ($city as $v) {?>
                                                    <option value="<?=$v->city_id?>"
                                                        <?=$v->city_id == $details['city_id'] ? 'selected' : false?>>
                                                        <?=$v->city_name?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group pull-right m-2">
                                <input type="hidden" name="submit" value="1" />
                                <button type="submit" class="btn btn-info btn-sm px-4" id="">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/staffmanager.js')?>"></script>