<div class="modal  center-modal fade" id="mdl-user-model">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-info mb-0"><i class="fa fa-pencil"></i> Edit User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" autocomplete="off" id="frm-edit-user" onsubmit="return false">
                    <input type="hidden" id="uid" name="uid">
                    <div class="row">
                        <div class="col-md-4">

                            <div class="form-group">
                                <label for="logo" class="control-label"> Logo <span class='mandatory'>
                                        *</span></label>
                                <div class=" mt-4 d-flex flex-column align-items-center justify-content-center">
                                    <div class='d-flex bg-secondary  align-items-center justify-content-center mb-2'
                                        style='max-height:175px; max-width:200px;'>
                                        <img src="<?=base_url('/public/images/avatar/avatar-1.png')?>" id="logo"
                                            class="logo " style='max-height:100%; max-width:100%;'>
                                    </div>

                                    <input type="file" class='d-none' name="logo" id="user_img"
                                        accept=".png, .jpg, .jpeg, .gif, .svg">
                                    <a type="text" class="btn btn-info btn-sm" id="cho_img">Choose Logo</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">First Name<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="first_name" class="form-control" id="first_name"
                                            placeholder="Enter your first name"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Last Name<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="last_name" class="form-control" id="last_name"
                                            placeholder="Enter your last name"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Email<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Eg: india for India"
                                            data-validation-required-message="This field is required" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Phone no.<span class='mandatory'>*</span></label>
                                    <div class="controls">
                                        <div class="input-group">
                                            <select class="input-group-text select-code" name='country_code'
                                                id='phone-code'>
                                                <option value="91">+91</option>
                                            </select>
                                            <input type="number" name="phone" class="form-control" id='phone'
                                                placeholder="Enter your  phone NO.."
                                                data-validation-required-message="This field is required" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" onclick='update_details_user()'
                                class="btn btn-info btn-sm px-4 pull-right">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>