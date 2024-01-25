<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.search {
    position: absolute;
    top: 8px;
    right: 8px;

}

.bg-orange {
    background-color: #f01935 !important;
}
</style>
<div class="alert alert-success" style="display:none" id="success-msg"></div>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; Return`s</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <input type="hidden" name='trans_type_id' id='trans_type_id' value='9'>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="return_details" class="table" width="100%">
                        <thead>
                            <div class="row mb-2" id='srch-filter'>
                                <div class="col-md-3 form-group">
                                    <div class="input-group">
                                        <select name="search" class='form-control' id="cust_name"
                                            onchange='reload_list()'>
                                        </select>
                                        <span class='search'><i class="fa fa-search"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <span class="input-group"> <i class="fa fa-filter  btn btn-sm btn-info"></i></span>
                                    <div class="input-group" style='left: 34px;    top: -30px;'>
                                        <button type="button" class="btn btn-sm  btn-info pull-right" id="daterange">
                                            <span>
                                                <i class="fa fa-calendar"></i> Select Date
                                            </span>
                                            <i class="fa fa-caret-down"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php $Active_role_id = $session->get('user_details')['active_role_id'];?>
                                <?php if ($Active_role_id == 2) {?>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class='col-md-6'>
                                            <select name="branch" class="form-control select2" id='brid'
                                                style='width: 390.237px;'>
                                                <option value=""> Select Branch / Address</option>
                                                <?php foreach ($branches as $v) {?>
                                                <?php $address = $v->address . ', ' . $v->state_name . ', ' . $v->city_name?>
                                                <option value="<?=$v->id?>">
                                                    <?=$v->name . "/" . $address?>
                                                </option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style='display:none ' id='div-show'>
                                            <a id='branch-id' href="" class="btn btn-sm  btn-info pull-right">
                                                New Return </a>
                                        </div>
                                    </div>
                                    <?php } else {?>
                                    <div class="col-md-6">
                                        <?php $brnid = $session->get('user_details')['branch_id'];?>
                                        <a href="<?=base_url("/transaction/returns/create_return/$brnid")?>"
                                            class="btn btn-sm  btn-info pull-right">
                                            New Return </a>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <tr>
                                <th width='20%' scope="col">Ref No. / Date</th>
                                <th width='20%' scope="col">CN No.</th>
                                <th width='20%' scope="col">Created By</th>
                                <th width='18%' scope="col">Customer</th>
                                <th width='10%' scope="col">Taxable Amt</th>
                                <th width='10%' scope="col">GST Total</th>
                                <th width='10%' scope="col">Net Total</th>
                                <th width='10%' scope="col">Status</th>
                                <th width="100" class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/assets/vendor_components/moment/min/moment.min.js')?>"></script>
<script src="<?=base_url('public/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js')?>"></script>
<script>
$(function() {
    $('.select2').select2({
        templateResult: formatSearch,
    }).on('change', function() {
        $('#div-show').show();
        let id = $(this).val();
        let url = base_url("/transaction/returns/create_return/") + id;
        $('#branch-id').attr('href', url);
        $("#return_details").DataTable().ajax.reload();
    })
})

function formatSearch(item) {
    var selectionText = item.text.split("/");
    var $returnString = $('<span><b>' + selectionText[0] + '</b></br>' + selectionText[1] + '</span>');
    return $returnString;
};
</script>
<script src="<?=base_url('public/custom/js/transaction/returns/returntable.js')?>"></script>