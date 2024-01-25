<div class="modal  center-modal fade" id="mdl_edit_country">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-info mb-0"><i class="fa fa-pencil"></i> Update Country</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" autocomplete="off" id="frm-edit-country" onsubmit="return false">
                    <input type="hidden" id="country_id" name="country_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Country Name<span class='mandatory'>*</span></label>
                                <div class="controls">
                                    <input type="text" name="country_name" class="form-control" id="e_cname"
                                        placeholder="Enter country name"
                                        data-validation-required-message="This field is required" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Sort Name<span class='mandatory'>*</span></label>
                                <div class="controls">
                                    <input type="text" name="sort_name" class="form-control" id="e_s_name"
                                        placeholder="Eg: IN for India"
                                        data-validation-required-message="This field is required" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Slug<span class='mandatory'>*</span></label>
                                <div class="controls">
                                    <input type="text" name="slug" class="form-control" id="e_slug"
                                        placeholder="Eg: india for India"
                                        data-validation-required-message="This field is required" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Phone Code<span class='mandatory'>*</span></label>
                                <div class="controls">
                                    <input type="number" name="phone_code" class="form-control" id='e_phone'
                                        placeholder="Eg: 91 for India"
                                        data-validation-required-message="This field is required" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-sm px-4 pull-right">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>