<div class="modal center-modal fade" id="mdl_edit_view_branch">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0  text-info"><i class="fa fa-pencil"></i> Update Branch Address</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" id="edit_form_view_branch" autocomplete="off" onsubmit="return false">
                    <input type="hidden" name='e_address_id' id='e_address_id'>
                    <input type="hidden" name='e_parent_id' id='e_parent_id'>
                    <div class="mx-5">
                        <div class="form-group">
                            <label for="username" class="control-label">Branch Name<span
                                    class='mandatory'>*</span></label>
                            <div class="controls">
                                <input type="text" name="name" class="form-control" id="e_b_name"
                                    placeholder="Enter your name "
                                    data-validation-required-message="This field is required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="control-label">Branch Email<span
                                    class='mandatory'>*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="ti-email"></i></span>
                                <input type="email" name="email" class="form-control" id="e_email"
                                    placeholder="Enter your email address"
                                    data-validation-required-message="This field is required" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastname" class="control-label">Contact No.<span
                                    class='mandatory'>*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="number" name="phone" class="form-control" id="e_mobile_no"
                                    data-inputmask='"mask": "999-999-9999"' data-mask
                                    placeholder="Enter your phone number"
                                    data-validation-required-message="This field is required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="control-label">GST No.<span class='mandatory'>*</span></label>
                            <div class="controls">
                                <input name="gst_no" class="form-control" id="e_gst_no" placeholder="Type your gst no"
                                    data-validation-required-message="This field is required"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="control-label">Address<span class='mandatory'>*</span></label>
                            <div class="controls">
                                <textarea name="address" class="form-control" id="e_address"
                                    placeholder="Type your Address"
                                    data-validation-required-message="This field is required"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-dark pull-right " id="">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>