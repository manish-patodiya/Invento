<div class="modal  center-modal fade" id="mdl_edit_cities">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-info mb-0"><i class="fa fa-pencil"></i> Update City</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="m-3">
                <form method="post" autocomplete="off" id="edit_city" onsubmit="return false">
                    <input type="hidden" id="e_city_id" name="city_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class=" form-group">
                                <label for="email" class="control-label">City Name<span
                                        class='mandatory'>*</span></label>
                                <div class=" controls input-group">
                                    <div class="input-group-prepend">
                                    </div>
                                    <input type="text" name="city_name" class="form-control" id="e_city"
                                        placeholder="Enter city name"
                                        data-validation-required-message="This field is required" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="username" class="control-label">State<span
                                        class='mandatory'>*</span></label>
                                <div class="controls">
                                    <select class='form-control ' name="state_id" id="e_state"
                                        data-validation-required-message="This field is required"
                                        data-live-search="true">
                                        <option value="">Select state</option>
                                        <?php foreach ($state as $key => $value) {?>
                                        <option value="<?=$value->state_id?>">
                                            <?=$value->state_name?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="submit" value="1" />
                        <button type="submit" class="btn btn-info  btn-sm px-4 pull-right">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>