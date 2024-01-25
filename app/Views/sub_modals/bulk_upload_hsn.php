<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    style="display: none;" id='mdl-bulk-upload-hsns'>
    <div class="modal-dialog modal-lg ribbon-box">
        <div class="ribbon-two ribbon-two-primary"><span>Upload</span></div>
        <div class="modal-content">
            <div class="modal-body">
                <a href="<?=base_url('public/uploads/sample_files/hsn_csv_sample.csv');?>"
                    class="btn btn-success btn-sm pull-right"><i class="fa fa-download"></i> Download Sample
                    File</a>
                <div class="box-body">
                    <p class="mb-0 pt-20">
                        <span class="text-warning">The first line in downloaded csv file should remain as it is.
                            Please do not change the order of columns.</span><br>The correct column order is
                        <span class="text-info">
                            (HSN Code, HSN Code 4 Digits, Details, GST Rate)</span>
                        &amp; you must follow this.<br>
                        Please make sure the csv file is UTF-8 encoded and not saved with byte order mark (BOM).
                    </p>
                    <hr>
                    <div class='alert alert-danger' style='display:none;' id='bulk-upld-errors'></div>
                    <form id='frm-bulk-upload-hsn'>
                        <div class='form-group controls'>
                            <label>Upload CSV File</label>
                            <input class="form-control" name='csv_content' type="file" accept='.xlsx, .xls, .csv' />
                        </div>
                        <div class='form-group'>
                            <button type='submit' id='btn-upld' class='btn btn-success btn-sm'>Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm pull-right" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>