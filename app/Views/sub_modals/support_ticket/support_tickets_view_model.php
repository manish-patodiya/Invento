<div class="modal center-modal  fade" id="support_tickets_view">
    <div class="modal-dialog  modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-start">
                <h5> <span class="subject" style="word-wrap: break-word;"></span></h5>
                <button type="button" class="btn-close pull-right" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 m-2">
                <div class=''>
                    <input type='hidden' class='hidden' name='compnay_id' />

                    <div class='form-group row m-1'>
                        <div class="col-md-12 mb-12">
                            <span id="description" style="word-wrap: break-word;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class='row'>
                    <span class='col-md-6 text-left'>
                        <label for="">
                            Created :<span id='date'></span>
                        </label><br>
                        <label for="">
                            Status :<span id=status></span>
                        </label>
                    </span>
                    <span class='col-md-6 text-right'>
                        <h5 id='branch_name'></h5>
                        <h6 class='' id='branch_email'></h6>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>