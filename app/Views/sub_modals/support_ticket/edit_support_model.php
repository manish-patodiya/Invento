<div class="modal center-modal fade" id="mdl_edit_suppot">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-info mb-0"><i class="fa fa-pencil"></i> Update Support Ticket</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" id="edit_support_detail" autocomplete="off" onsubmit="return false">
                    <input type="hidden" name='supprot_id' id='supprot_id'>
                    <div class="mx-5">
                        <div class="form-group">
                            <label for="subject" class="col-md-2 control-label">Subject<span
                                    class='mandatory'>*</span></label>
                            <div class="controls col-md-12">
                                <input type="text" name="subject" class="form-control " id="e_subject"
                                    placeholder="Enter your subject"
                                    data-validation-required-message="This field is required">
                            </div>
                        </div>

                        <div class='form-group'>
                            <label for="mediafile" class="col-md-2 control-label">Description<span
                                    class='mandatory'>*</span></label>

                            <div class="controls col-md-12 mb-12">
                                <textarea type="text" name="descriptions" class="form-control" id="e_descriptions"
                                    rows='8' placeholder="Type your description"
                                    data-validation-required-message="This field is required"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="submit" value="1" />
                            <button type="submit" class="btn btn-dark pull-right" id="">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>