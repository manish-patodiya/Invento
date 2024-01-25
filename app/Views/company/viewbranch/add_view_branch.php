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

.invalid {
    color: red;
}

.valid {
    color: green;
}

.select-code {
    background-color: #fff;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Create Branch</h4>
                </div>
                <a href="#" onclick="history.back();" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>
                <!-- <div class="d-inline-block float-right">
                    <a href="<?php //echo base_url("/branches") ?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i>
                        Branch List
                    </a>
                </div> -->

            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <form method="post" autocomplete="off" id="add-view-branches-details" onsubmit="return false">
                            <input type="hidden" value="<?=$cid?>" name="cid" id="cid">
                            <div class="m-3 row">
                                <div class='col-md-12'>
                                    <h4 class="text-info mb-0">Branch Info</h4>
                                    <hr class="my-15">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="password" class="control-label">Branch Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="branches_name" class="form-control"
                                                        id="branches_name" placeholder="Enter your branch name  "
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="companyname" class="control-label">GST No</label>
                                                <div class="controls">
                                                    <input type="text" name="gst_no" class="form-control" id="gst_no"
                                                        placeholder="Enter your gst no">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class=" form-group">
                                                <label for="email" class="control-label">Email<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <div class=" input-group">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i
                                                                    class="ti-email"></i></span>
                                                            <input type="email" name="email" class="form-control"
                                                                id="email" placeholder="Enter your email address"
                                                                data-validation-required-message="This field is required" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile No<span class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <select class="input-group-text select-code" name='country_code'
                                                            id='phone-code'>
                                                            <option value="91">+91</option>
                                                        </select>
                                                        <input type="number" name="mobile_no" class="form-control"
                                                            id="mobile_no" data-inputmask='"mask": "999-999-9999"'
                                                            data-mask placeholder="Enter your mobile number"
                                                            data-validation-required-message="This field is required" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="companyname" class="control-label">IEC Code</label>
                                                <div class="controls">
                                                    <input type="text" name="ieccode" class="form-control" id="ieccode"
                                                        placeholder="Enter your iec-code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country" class="control-label">Country<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="country" class="form-control" id='country'
                                                    data-validation-required-message="This field is required">
                                                    <option value="">Select state</option>
                                                    <?php foreach ($country as $v) {?>
                                                    <option value="<?=$v->id?>" <?=$v->id == 101 ? 'selected' : ''?>>
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
                                                <select name="state" class="form-control" id='state'
                                                    data-validation-required-message="This field is required">
                                                    <option value="">Select state</option>
                                                    <?php foreach ($state as $v) {?>
                                                    <option value="<?=$v->state_id?>"><?=$v->state_name?></option>
                                                    <?php }?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">City<span class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="citie" class="form-control" id='citie'
                                                    data-validation-required-message="This field is required">
                                                    <option value="">Select city</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pincode" class="control-label">Pincode<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="number" name="pincode" class="form-control" id="pincode"
                                                    placeholder="Enter your pincode" onfocusout="getPincode()"
                                                    data-validation-required-message="This field is required" />
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
                                                    data-validation-required-message="This field is required"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <h4 class="text-info mb-0"><i class="ti-user"></i> Branch User</h4>
                                    <hr class="my-15">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="username" class="control-label">Username<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="username" class="form-control"
                                                        id="username" placeholder="Enter username"
                                                        data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="firstname" class="control-label">First Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="firstname" class="form-control"
                                                        id="firstname" placeholder="Enter your firstname"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="lastname" class="control-label">Last Name<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name="lastname" class="form-control"
                                                        id="lastname" placeholder="Enter your lastname"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 px-4">
                                            <div class="form-group">
                                                <label for="password" class="control-label">Password<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" placeholder="Enter password"
                                                        data-validation-required-message="This field is required" />
                                                </div>
                                            </div>

                                            <div style='font-size:14px'>
                                                Make your password strong by adding:
                                                <ul style='color:#6d6d71;font-size:12px'>
                                                    <li id="length" class="invalid">Minimum 8 characters (letters &
                                                        numbers)
                                                    </li>
                                                    <li id="capital" class="invalid">Minimum one capital letter (A-Z)
                                                    </li>
                                                    <li id="special" class="invalid">Minimum special character (@ $ ! #
                                                        % *
                                                        ?
                                                        & )</li>
                                                    <li id="number" class="invalid">Minimum one number (0-9)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <button type="submit" class="btn btn-info pull-right" id="btn-save">
                                        Submit
                                    </button>
                                </div>
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
<script src="<?=base_url('public/custom/js/company.js')?>"></script>

<script>
var myInput = document.getElementById("password");
var special = document.getElementById("special");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");


// When the user starts to type something inside the password field
myInput.onkeyup = function() {
    // Validate specialCaseLetters [@ $ ! # % *?&]
    var specialCaseLetters = /[@ $ ! # % * ? &]/g;
    if (myInput.value.match(specialCaseLetters)) {
        special.classList.remove("invalid");
        special.classList.add("valid");
    } else {
        special.classList.remove("valid");
        special.classList.add("invalid");
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if (myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if (myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

    // Validate length
    if (myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
    } else {
        $('#submit').removeAttr('disabled', 'disabled');
        length.classList.remove("valid");
        length.classList.add("invalid");
    }
}
</script>