<div class="modal  center-modal fade" id="mdl-deactive-user-model">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h4 class="text-info mb-0"><i class="fa fa-user"></i> Deactivete User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div> -->
            <div class="m-3">
                <form method="post" autocomplete="off" id="user-deactive-status-updata" onsubmit="return false">
                    <input type="hidden" id="user-id" name="uid">
                    <div class="row">
                        <span class='text-center' style='font-size:30px'> Are you sure?</span>
                    </div>
                    <div class="row">
                        <span class='text-center mt-5' style='font-size:15px'>You want deactivate user</span>
                    </div>

                    <div class="row mt-5 m-1">
                        <div class="form-group">
                            <label class="control-label">Deactivete reason<span class='mandatory'>*</span></label>
                            <div class="controls">
                                <textarea name="deactive" class="form-control" id="deactive" rows="4"
                                    placeholder="Enter you Deactivete reason for user"
                                    data-validation-required-message="This field is required"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger btn-sm" onclick='deactive_user();'
                            id='btn-save'>Okay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>