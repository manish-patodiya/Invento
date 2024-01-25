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
                    <h4 class="page-title"><i class="fa fa-plus"></i> General Setting </h4>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="card card-default">
                <div class="m-3">
                    <form method="post" autocomplete="off" id="bran_updeted_detail" onsubmit="return false">
                        <input type="hidden" name='company_id' value="<?=$data['company_id']?>">
                        <input type="hidden" name='user_id' value="<?=$data['user_id']?>">
                        <input type="hidden" name='branches' id='branches_id' value="<?=$data['id']?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password" class="control-label">Branch
                                        Name<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="branches_name" class="form-control" id="branches_name"
                                            placeholder="Enter your branch name" value="<?=$data['name']?>"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="companyname" class="control-label">GST
                                        NO.</label>
                                    <div class="controls">
                                        <input type="text" name="gst_no" class="form-control" id="gst_no"
                                            placeholder="Enter your gst no" value="<?=$data['gst_no']?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class=" form-group">
                                    <label for="email" class="control-label">Email<span
                                            class='mandatory'>*</span></label>
                                    <div class=" input-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="ti-email"></i></span>
                                            <input type="email" name="email" class="form-control" id="email"
                                                placeholder="Enter your email address" value="<?=$data['email']?>"
                                                data-validation-required-message="This field is required" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile_no">Mobile No<span class='mandatory'>*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                        <input type="number" name="mobile_no" class="form-control" id="mobile_no"
                                            data-inputmask='"mask": "999-999-9999"' data-mask
                                            value="<?=$data['phone']?>" placeholder="Enter your mobile number"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="companyname" class="control-label">IEC
                                        code</label>
                                    <div class="controls">
                                        <input type="text" name="ieccode" class="form-control" id="ieccode"
                                            placeholder="Enter your iec-code" value="<?=$data['iec_code']?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country" class="control-label">Country
                                        <span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <select name="country" class="form-control" id='country'
                                            data-validation-required-message="This field is required">
                                            <option value="">Select state</option>
                                            <?php foreach ($country as $v) {?>
                                            <option value="<?=$v->id?>"
                                                <?=$v->id == $data['country'] ? 'selected' : ''?>>
                                                <?=$v->name?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="companyname" class="control-label">State<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <select name="state" class="form-control" id='g-state'
                                            data-validation-required-message="This field is required">
                                            <option value="">Select state</option>
                                            <?php foreach ($state as $v) {?>
                                            <option value="<?=$v->state_id?>"
                                                <?=$v->state_id == $data['state_id'] ? 'selected' : false?>>
                                                <?=$v->state_name?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">City<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <select name="citie" class="form-control" id='g-citie'
                                            data-validation-required-message="This field is required">
                                            <option value="">Select city</option>
                                            <?php foreach ($city as $v) {?>
                                            <option value="<?=$v->city_id?>"
                                                <?=$v->city_id == $data['city_id'] ? 'selected' : false?>>
                                                <?=$v->city_name?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pincode" class="control-label">Pincode <span class='mandatory'>
                                            *</span></label>
                                    <div class="controls">
                                        <input type="number" name="pincode" class="form-control" id="pincode"
                                            placeholder="Enter your pincode" value='<?=$data['pincode']?>'
                                            onfocusout="getPincode()"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="address" class="control-label">Address<span
                                            class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <textarea name="address" class="form-control" id="address"
                                            placeholder="Type your address" rows='1'
                                            data-validation-required-message="This field is required"><?=$data['address']?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-sm px-4 pull-right " id="">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/generalsetting/branch_general_setting.js')?>"></script>