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
                <!-- <div class="d-inline-block float-right">
                    <a href="<?=base_url('/templates');?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i>
                        Template List</a>
                </div> -->
            </div>
        </div>
        <section class="content">

            <!-- mein body -->
            <div class="row">
                <div class="col-12 col-lg-12 col-xl-12">
                    <?php if ($session->user_details['branch_id'] != "0") {?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a class="active" href="#branch_general_setting" data-bs-toggle="tab">General
                                    Setting</a></li>
                            <li><a href="#trans_setting" data-bs-toggle="tab">Transaction Setting</a></li>
                            <li><a href="#branch_invoice_concept" data-bs-toggle="tab">Invoice Concept</a></li>
                        </ul>

                        <div class="tab-content">

                            <div class="active tab-pane" id="branch_general_setting">
                                <div class="col-md-12">
                                    <div class="card card-default">
                                        <div class="m-3">
                                            <h4 class="text-info mb-0">Branch Info</h4>
                                            <hr class="my-15">
                                            <form method="post" autocomplete="off" id="bran_updeted_detail"
                                                onsubmit="return false">
                                                <input type="hidden" name='company_id' value="<?=$data['company_id']?>">
                                                <input type="hidden" name='user_id' value="<?=$data['user_id']?>">
                                                <input type="hidden" name='branches' id='branches_id'
                                                    value="<?=$data['id']?>">
                                                <!-- <div class="m-3">
                                                    <h4 class="text-info mb-0">Branch Info</h4>
                                                    <hr class="my-15"> -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password" class="control-label">Branch
                                                                Name<span class='mandatory'>*</span></label>
                                                            <div class="controls">
                                                                <input type="text" name="branches_name"
                                                                    class="form-control" id="branches_name"
                                                                    placeholder="Enter your branch name"
                                                                    value="<?=$data['name']?>"
                                                                    data-validation-required-message="This field is required" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="companyname" class="control-label">GST
                                                                NO.</label>
                                                            <div class="controls">
                                                                <input type="text" name="gst_no" class="form-control"
                                                                    id="gst_no" placeholder="Enter your gst no"
                                                                    value="<?=$data['gst_no']?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class=" form-group">
                                                            <label for="email" class="control-label">Email<span
                                                                    class='mandatory'>*</span></label>
                                                            <div class=" input-group">
                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text"><i
                                                                            class="ti-email"></i></span>
                                                                    <input type="email" name="email"
                                                                        class="form-control" id="email"
                                                                        placeholder="Enter your email address"
                                                                        value="<?=$data['email']?>"
                                                                        data-validation-required-message="This field is required" />
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mobile_no">Mobile No<span
                                                                    class='mandatory'>*</span></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i
                                                                        class="fa fa-phone"></i></span>
                                                                <input type="number" name="mobile_no"
                                                                    class="form-control" id="mobile_no"
                                                                    data-inputmask='"mask": "999-999-9999"' data-mask
                                                                    value="<?=$data['phone']?>"
                                                                    placeholder="Enter your mobile number"
                                                                    data-validation-required-message="This field is required" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="companyname" class="control-label">IEC
                                                                code</label>
                                                            <div class="controls">
                                                                <input type="text" name="ieccode" class="form-control"
                                                                    id="ieccode" placeholder="Enter your iec-code"
                                                                    value="<?=$data['iec_code']?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="address" class="control-label">Address<span
                                                            class='mandatory'>*</span></label>
                                                    <div class="controls">
                                                        <textarea name="address" class="form-control" id="address"
                                                            placeholder="Type your address"
                                                            data-validation-required-message="This field is required"><?=$data['address']?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="companyname" class="control-label">State<span
                                                                    class='mandatory'>*</span></label>
                                                            <div class="controls">
                                                                <select name="state" class="form-control" id='state'
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
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="control-label">City<span
                                                                    class='mandatory'>*</span></label>
                                                            <div class="controls">
                                                                <select name="citie" class="form-control" id='citie'
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
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                                        id="">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="trans_setting">
                                <div class="card card-default">
                                    <div class="m-3">
                                        <form method="post" autocomplete="off" id="trans_details"
                                            onsubmit="return false">
                                            <h4 class="text-info mb-0">Transaction Setting</h4>
                                            <hr class="my-15">
                                            <?php foreach ($trans_type as $k => $v) {?>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="username"
                                                            class="control-label"><?=$v->title?></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!-- <div class=""> -->
                                                        <input type="hidden" name='trans_type_id[]'
                                                            value='<?=isset($v->id) ? $v->id : ""?>'>
                                                        <input type="text" name="prefix[]" class="form-control"
                                                            id="prefix" placeholder="Type your prefix"
                                                            value="<?=isset($v->prefix) ? $v->prefix : ""?>">
                                                        <!-- </div> -->
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="username" class="control-label">Starting No.</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <!-- <div class=""> -->
                                                        <input type="text" name="start_no[]" class="form-control"
                                                            id="start_no" placeholder="Type Starting No."
                                                            value="<?=isset($v->start_no) ? $v->start_no : $start_no->start_no?>">
                                                        <!-- </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                                    id="">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="branch_invoice_concept">
                                <div class="card card-default">
                                    <div class="m-3">
                                        <form method="post" autocomplete="off" id="branch_invoice_concept_details"
                                            onsubmit="return false">
                                            <h4 class="text-info mb-0">Invoice Concept</h4>
                                            <hr class="my-15">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="demo-radio-button">
                                                        <?php $n = 1;foreach ($trans_concept_master as $k => $v) {?>
                                                        <input name="group4" type="radio" id="radio_<?=$n?>"
                                                            class="radio-col-success" value="<?=$v->id?>"
                                                            <?=$v->id == $data['trans_concept_id'] ? "Checked" : ""?>>
                                                        <label for="radio_<?=$n?>"><?=$v->title?></label>
                                                        <?php $n++;}
    ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                                        id="">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>

                <!-- End of company general setting view -->

                <!-- Starting of branch general setting view -->

                <?php } else {?>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li><a class="active" href="#general_setting" data-bs-toggle="tab">General Setting</a></li>
                        <li><a href="#start_no" data-bs-toggle="tab">Transaction Setting</a></li>
                        <li><a href="#invoice_concept" data-bs-toggle="tab">Invoice Concept</a></li>
                    </ul>

                    <div class="tab-content">

                        <div class="active tab-pane" id="general_setting">
                            <div class="col-md-12">
                                <div class="card card-default">
                                    <div class="m-3">
                                        <h4 class="text-info mb-0">Company Info</h4>
                                        <hr class="my-15">
                                        <form method="post" autocomplete="off" id="edit_general_settings"
                                            onsubmit="return false">
                                            <input type="hidden" name='company' id='company_id'
                                                value="<?=$data['id']?>">
                                            <input type="hidden" name='user_id' id='user_id'
                                                value="<?=$data['user_id']?>">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="logo" class="control-label"> Logo</label>
                                                        <div
                                                            class=" d-flex flex-column align-items-center justify-content-center">
                                                            <div class='d-flex bg-secondary  align-items-center justify-content-center mb-2'
                                                                style='height:200px; width:200px;'>
                                                                <img src="<?=$data['logo'] ?: base_url('/public/uploads/image_found/logo1.png')?>"
                                                                    id="logo" class="logo "
                                                                    style='max-height:100%; max-width:100%;'>
                                                            </div>

                                                            <input type="file" class='d-none' name="logo" id="user_img"
                                                                accept=".png, .jpg, .jpeg, .gif, .svg">
                                                            <a type="text" class="btn btn-info btn-sm"
                                                                id="cho_img">Choose Logo</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="companyname" class="control-label">Company
                                                                    Name<span class='mandatory'>*</span></label>
                                                                <div class="controls">
                                                                    <input type="text" name="companyname"
                                                                        class="form-control" id="companyname"
                                                                        placeholder="Enter your company name"
                                                                        value="<?=$data['name']?>"
                                                                        data-validation-required-message="This field is required">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Email<span
                                                                        class='mandatory'>*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text"><i
                                                                            class="ti-email"></i></span>
                                                                    <input type="email" name="email"
                                                                        class="form-control" id="email"
                                                                        placeholder="Enter your email"
                                                                        value="<?=$data['email']?>"
                                                                        data-validation-required-message="This field is required" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group ">
                                                                <label for="mobile_no" class="control-label">Mobile
                                                                    No<span class='mandatory'>*</span></label>
                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text"><i
                                                                            class="fa fa-phone"></i></span>
                                                                    <input type="number" name="mobile_no"
                                                                        class="form-control" id="mobile_no"
                                                                        data-inputmask='"mask": "999-999-9999"'
                                                                        data-mask value="<?=$data['mobile']?>"
                                                                        placeholder="Enter your mobile no:"
                                                                        data-validation-required-message="This field is required" />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr>
                                            <!-- <div class="row">

                                                </div> -->

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="companyname" class="control-label">IEC
                                                            code</label>
                                                        <div class="controls">
                                                            <input type="text" name="ieccode" class="form-control"
                                                                id="ieccode" placeholder="Enter your ice-code"
                                                                value='<?=$data['iec_code']?>'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Website (URL)</label>
                                                        <div class="controls">
                                                            <input type="text" name="website" class="form-control"
                                                                id="url" placeholder="Enter your website url"
                                                                value='<?=$data['website_url']?>' />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="address" class="control-label">Address<span
                                                            class='mandatory'>*</span></label>
                                                    <div class="controls">
                                                        <textarea name="address" class="form-control" id="address"
                                                            placeholder="Type your address"
                                                            data-validation-required-message="This field is required"><?=$data['address']?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="companyname" class="control-label">State<span
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
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">City<span
                                                                class='mandatory'>*</span></label>
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
                                            <!-- <div class="row"> -->
                                            <div class="form-group">
                                                <input type="hidden" name="submit" value="1" />
                                                <button type="submit" class="btn btn-info px-4 pull-right  btn-sm"
                                                    id="">
                                                    Update
                                                </button>
                                            </div>
                                            <!-- </div>  -->

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="start_no">
                            <div class="card card-default">
                                <div class="m-3">
                                    <form method="post" autocomplete="off" id="edit_start_no" onsubmit="return false">
                                        <h4 class="text-info mb-0">Transaction Setting</h4>
                                        <hr class="my-15">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="username" class="control-label">Start No.</label>
                                                </div>
                                            </div>

                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <!-- <div class=""> -->
                                                    <input type="text" name="start_no" class="form-control"
                                                        id="start_no" placeholder="Type your start no."
                                                        value="<?=$data['start_no']?>">
                                                    <!-- </div> -->
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info btn-sm px-4 pull-right " id="">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="invoice_concept">
                            <div class="card card-default">
                                <div class="m-3">
                                    <form method="post" autocomplete="off" id="invoice_concept_details"
                                        onsubmit="return false">
                                        <h4 class="text-info mb-0">Invoice Concept</h4>
                                        <hr class="my-15">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="demo-radio-button">
                                                    <?php $n = 1;foreach ($trans_concept_master as $k => $v) {?>
                                                    <input name="group4" type="radio" id="radio_<?=$n?>"
                                                        class="radio-col-success" value="<?=$v->id?>"
                                                        <?=$v->id == $data['trans_concept_id'] ? "Checked" : ""?>>
                                                    <label for="radio_<?=$n?>"><?=$v->title?></label>
                                                    <?php $n++;}
    ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-info btn-sm px-4 pull-right "
                                                    id="">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <?php }?>

                <!-- /.nav-tabs-custom -->
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/general_setting.js')?>"></script>