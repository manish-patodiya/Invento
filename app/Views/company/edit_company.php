<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<script src="<?=base_url('public/assets/vendor_plugins/phone_no_format/phone_format.jquery.min.js')?>"></script>
<style>
.btn-lg {
    font-size: 1.286rem;
    padding: 6px 32px;
}

.h-100 {
    height: 184px !important;
}

.w-100 {
    width: 300px !important;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-pencil"></i> Edit Company</h4>
                </div>
                <a href="#" onclick="history.back();" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3">
                            <h4 class="text-info mb-0">Company Info</h4>
                            <hr class="my-15">
                            <form method="post" autocomplete="off" id="edit_company_detail" onsubmit="return false">
                                <input type="hidden" name='company' id='company_id' value="<?=$data['id']?>">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo" class="control-label"> Logo<span
                                                    class='mandatory'>*</span></label>
                                            <div class=" d-flex flex-column align-items-center justify-content-center">
                                                <div class='d-flex bg-secondary  align-items-center justify-content-center mb-2'
                                                    style='max-height:200px; max-width:200px;'>
                                                    <img src="<?=$data['logo'] ?: base_url('/public/uploads/image_found/logo1.png')?>"
                                                        id="logo" class="logo "
                                                        style='max-height:100%; max-width:100%;'>
                                                </div>
                                                <input type="file" class='d-none' name="logo" id="user_img"
                                                    accept=".png, .jpg, .jpeg, .gif, .svg">
                                                <a type="text" class="btn btn-info btn-sm" id="cho_img">Upload Logo</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="companyname" class="control-label">Company Name <span
                                                            class='mandatory'>*</span></label>
                                                    <div class="controls">
                                                        <input type="text" name="companyname" class="form-control"
                                                            id="companyname" placeholder="Enter your company name"
                                                            value="<?=$data['name']?>"
                                                            data-validation-required-message="This field is required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_date">License Expiry Date <span
                                                            class='mandatory'>*</span></label>
                                                    <div class="controls">
                                                        <input type="date" name="license_date" id="license_date"
                                                            class="form-control" value="<?=$data['license_date']?>"
                                                            placeholder="Enter your license expiry date"
                                                            data-validation-required-message="This field is required" />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email <span
                                                            class='mandatory'>*</span></label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i class="ti-email"></i></span>
                                                        <input type="email" name="email" class="form-control" id="email"
                                                            placeholder="Enter your email" value="<?=$data['email']?>"
                                                            data-validation-required-message="This field is required" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    <label for="mobile_no" class="control-label">Mobile No <span
                                                            class='mandatory'>*</span></label>
                                                    <div class="input-group mb-3">
                                                        <!-- <span class="input-group-text"><i class="fa fa-phone"></i></span> -->
                                                        <select class="input-group-text select-code" name='phonecode'>
                                                            <option value="+91">+91</option>
                                                        </select>
                                                        <input type="number" name="mobile_no" class="form-control"
                                                            id="mobile_no"
                                                            onKeyPress="if(this.value.length==11) return false;"
                                                            value="<?=$data['mobile']?>"
                                                            placeholder="Enter your mobile no:"
                                                            data-validation-required-message="This field is required" />

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="companyname" class="control-label">IEC code</label>
                                                    <div class="">
                                                        <input type="text" name="ieccode" class="form-control"
                                                            id="ieccode" placeholder="Enter your IEC-code"
                                                            value='<?=$data['iec_code']?>'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Website (URL)</label>
                                                    <div class="controls">
                                                        <input type="text" name="website" class="form-control" id="url"
                                                            placeholder="Enter your website url"
                                                            value='<?=$data['website_url']?>' />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="companyname" class="control-label"> CIN (Corporate Identity
                                                    Number)</label>
                                                <div class="controls">
                                                    <input type="text" name="cin" class="form-control" id="cin"
                                                        placeholder="Enter your Corporate Identity Number"
                                                        value='<?=$data['cin']?>'>
                                                </div>
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
                                            <label for="companyname" class="control-label">State <span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="state_id" class="form-control" id='state'
                                                    data-validation-required-message="This field is required">
                                                    <option value="">select state</option>
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
                                                <select name="citie_id" class="form-control" id='citie'
                                                    data-validation-required-message="This field is required">
                                                    <?php foreach ($city as $v) {?>
                                                    <option value="<?=$v->city_id?>"
                                                        <?=$v->city_id == $data['city_id'] ? 'selected' : false?>>
                                                        <?=$v->city_name?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                            </select>
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
                                            <label for="address" class="control-label">Address
                                                <span class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <textarea name="address" class="form-control" id="address"
                                                    placeholder="Type your address" rows='1'
                                                    data-validation-required-message="This field is required"><?=$data['address']?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="text-info mt mb-0"><i class="ti-user"></i> Company User </h4>
                                <input type="hidden" name='user_id' id='user_id' value="<?=$data['user_id']?>">
                                <hr class="my-15">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="username" class="control-label">Username<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" name="username" class="form-control" id="username"
                                                    placeholder="Enter username"
                                                    data-validation-required-message="This field is required"
                                                    value="<?=$data['username']?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstname" class="control-label">First Name<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" name="firstname" class="form-control" id="firstname"
                                                    placeholder="Enter your firstname"
                                                    data-validation-required-message="This field is required"
                                                    value="<?=$data['first_name']?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lastname" class="control-label">Last Name<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" name="lastname" class="form-control" id="lastname"
                                                    placeholder="Enter your lastname"
                                                    data-validation-required-message="This field is required"
                                                    value="<?=$data['last_name']?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="submit" value="1" />
                                    <button type="submit" class="btn btn-info px-4 pull-right  btn-sm" id="">
                                        Update
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
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/company.js')?>"></script>

<script>
$("input[name='mobile_no']").keyup(function() {
    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "$1$2$3"));
});
</script>