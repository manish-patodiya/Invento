<div class="modal center-modal fade" id="mdl_edit_address">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="mb-0 text-info"><i class="fa fa-pencil"></i> Update Company Address</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" id="edit_form" autocomplete="off" onsubmit="return false">
                    <input type="hidden" name='address_id' id='e_address_id'>
                    <div class="mx-5">
                        <div class="form-group">
                            <label for="username" class="control-label">Company Name<span
                                    class='mandatory'>*</span></label>
                            <div class="controls">
                                <input type="text" name="name" class="form-control" id="e_title"
                                    placeholder="Enter your name "
                                    data-validation-required-message="This field is required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="control-label">Company Email<span
                                    class='mandatory'>*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="ti-email"></i></span>
                                <input type="email" name="email" class="form-control" id="e_email"
                                    placeholder="Enter your email address"
                                    data-validation-required-message="This field is required" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lastname" class="control-label">Contact No<span
                                    class='mandatory'>*</span></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="number" name="phone" class="form-control" id="e_phone"
                                    data-inputmask='"mask": "999-999-9999"' data-mask
                                    placeholder="Enter your phone number"
                                    data-validation-required-message="This field is required" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="control-label">Website (URL)</label>
                            <div class="controls">
                                <input name="website_url" class="form-control" id="e_website_url"
                                    placeholder="Type your address"></input>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="submit" value="1" />
                            <button type="submit" class="btn btn-dark pull-right">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>