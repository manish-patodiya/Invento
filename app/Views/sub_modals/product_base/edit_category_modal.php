<div class="modal center-modal fade" id="mdl_edit_category">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Category</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" id="edit_form" autocomplete="off" onsubmit="return false">
                    <input type="hidden" name='cate_id' id='e_category_id'>
                    <div class="mx-5">
                        <div class="form-group">
                            <label for="username" class="control-label">Category Name<span
                                    class='mandatory'>*</span></label>
                            <div class="controls">
                                <input type="text" name="category_name" class="form-control" id="e_ctegory"
                                    placeholder="Entern your category name"
                                    data-validation-required-message="This field is required">
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