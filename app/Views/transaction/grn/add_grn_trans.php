<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
$prefix = isset($trans_type_info->prefix) && $trans_type_info->prefix ? "$trans_type_info->prefix-$trans_type_info->start_no" : $trans_type_info->start_no;
$gst_no = $session->get('user_details')['gst_no'] ?: 0;
$invoice_concept = $session->get('user_details')['branch_concept_id'] ?: $session->get('user_details')['company_concept_id'];
$trans_type = isset($trans_type_info->id) ? $trans_type_info->id : "";
$user_name = $session->get('user_details')['user_name']
?>

<!-- include custom css -->
<link rel="stylesheet" href="<?=base_url('public/custom/css/transaction.css')?>">

<!-- set value in js from php -->
<script>
const UT_CODES = JSON.parse('<?=json_encode(UT_CODES)?>');
const TAXES = JSON.parse('<?=json_encode(TAXES)?>');
const GST_NAMES = JSON.parse('<?=json_encode(GST_NAMES)?>');
let current_branch_gst_no = '<?=$gst_no?>';
let invoice_concept = <?=$invoice_concept?>;
let grn_inv_id = <?=$grn_inv_id?>;
</script>

<!-- main content -->
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center row">
                <div class="me-auto col-md-4">
                    <h4 class="page-title"><i class="fa fa-plus"></i> Create Transaction</h4>
                </div>
                <div class="col-md-8 ">
                    <a href="#" onclick="history.back();" class="pull-right">
                        <i class="fa fa-long-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3">
                            <form method="post" autocomplete="off" id="frm-create-transaction">
                                <div class="row">
                                    <h4 class="page-title col-md-4">Create
                                        <?=isset($trans_type_info->title) ? $trans_type_info->title : ""?>
                                        <span class='text-secondary'>(<?=$prefix?>)</span>
                                    </h4>
                                    <div class="page-title col-md-4">
                                        <select name="grn_inv_id" class='form-control' id="slc-est">
                                            <option value="">Select VPO</option>
                                            <?php foreach ($slc_grn as $val) {?>
                                            <option value="<?=$val->id?>"
                                                <?=$val->id == $grn_inv_id ? "selected" : false?>>
                                                <?=$val->invoice_prefix_id?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 text-end">
                                        <label class="control-label">Create Date </label>
                                        <input name='create_date' type="date" class='d-none' id="chs-date"
                                            value='<?=date('Y-m-d')?>' />
                                        <label class="control-label" id='lbl-date'>: <span><?=date('d M Y')?></span>
                                            <a href="#" id='curr_date'><i class="fa fa-edit text"
                                                    onclick="setDatepicker(this)"></i></a></label>
                                    </div>

                                    <hr>
                                    <input type="hidden" name='branch_address' value='<?=$branch->address?>'>
                                    <input type="hidden" name='prefix' value='<?=$prefix?>'>
                                    <input type="hidden" name='from_gst_no' value='<?=$gst_no?>' />
                                    <input type="hidden" name='taxes_json' id='taxes-json' value='' />
                                    <input type="hidden" name='transaction_type' value="<?=$trans_type?>" />
                                    <input type="hidden" name='start_no' value="<?=$trans_type_info->start_no?>" />
                                    <input type="hidden" name='user_name' value="<?=$user_name?>" />
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Vendor<span class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <select name="company" class='form-control' id="company"
                                                    onchange='get_branch_list(this.value)'>
                                                    <option value="">Select One</option>
                                                    <?php foreach ($company_address_list as $cal) {?>
                                                    <option value="<?=$cal->id?>"><?=ucwords($cal->name)?>
                                                    </option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Reference No.<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls">
                                                <input type="text" name='reference_no' class='form-control'
                                                    id='reference-no'
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Reference Date<span
                                                    class='mandatory'>*</span></label>
                                            <div class="controls mt-1">
                                                <input type="date" name='reference_date' class='col-md-12 form-control'
                                                    id='reference-date'
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class=' row'>
                                                <label class="col-md-6 control-label">Billing Address<span
                                                        class='mandatory'>*</span></label>
                                                <!-- <span class="col-md-6 demo-checkbox d-none" id='checkbox-show'>
                                                    <input type="checkbox" id="basic_checkbox_1" checked />
                                                    <label for="basic_checkbox_1">Same As Billing Address </label>
                                                </span> -->
                                            </span>
                                            <div class="controls">
                                                <select name="branch" class='form-control' id="branch"
                                                    onchange='set_gst_no_and_address(this)'>
                                                    <option value="">Select Billing Address</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='d-none' id='edit-bi-ad'>
                                            <span class='row'>
                                                <span class="col-md-10" id='bi_bi_adre'></span>
                                                <span class="col-md-2 text-end"><a href="#" id='edit-bi' class='d-none'>
                                                        <i class="fa fa-edit "></i></a></span>
                                            </span>
                                            <span> GST NO: <span id='bi_gst_no'></span></span>
                                        </div>
                                        <div class='d-none' id='up-bi-gst'>
                                            <div class="form-group">
                                                <label class="control-label">GST No.<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <input type="text" name='gst_no' class='form-control brnch-dtl'
                                                        id='gst_no'
                                                        data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                            <div class=' form-group'>
                                                <label class="col-md-6 control-label">Billing Address<span
                                                        class='mandatory'>*</span></label>
                                                <div class="controls">
                                                    <textarea name='billing_address' rows='2'
                                                        class='form-control brnch-dtl' id='billing-address'
                                                        data-validation-required-message="This field is required"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end">
                                            <button type='button' class='btn btn-sm btn-info text-end d-none'
                                                id='billi-adre'>Save</button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                        <div class="form-group ">
                                            <label class="control-label">Shipping Address </label>
                                            <div class="controls">
                                                <select name="sec_branch" class='form-control' id="select-sec-branch"
                                                    disabled onchange='set_gst_no_and_address(this,true)'>
                                                    <option value="">Select Shipping Address</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='d-none' id='edit-ship-ad'>
                                            <span class='row'>
                                                <span class="col-md-10" id='sec_ship_adre'></span>
                                                <span class="col-md-2 text-end d-none" id='show-edit'><a href="#"
                                                        id='edit-ship'> <i class="fa fa-edit "></i></a></span>
                                            </span>
                                            <span> GST NO: <span id='ship_gst_no'></span></span><br>
                                        </div>
                                        <div class='d-none' id='up-ship-gst'>
                                            <div class="form-group">
                                                <label class="control-label">GST No.</label>
                                                <div class="controls">
                                                    <input type="text" name='sec_gst_no' class='form-control brnch-dtl'
                                                        id='sec_gst_no'
                                                        data-validation-required-message="This field is required">
                                                </div>
                                            </div>
                                            <div class=' form-group'>
                                                <label class="control-label">Shipping Address</label>
                                                <div class="controls mt-1">
                                                    <textarea name='shipping_address' rows='2'
                                                        class='form-control brnch-dtl' id='shipping-address'
                                                        data-validation-required-message="This field is required"></textarea>
                                                </div>
                                            </div>
                                        </div> -->
                                    <!-- <div class="col-md-12 text-end">
                                        <button type='button' class='btn btn-sm btn-info text-end d-none'
                                            id='ship-adre'>Save</button>
                                    </div>
                                </div> -->
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <h4 for="username" class="control-label">Products</h4>
                                        <hr>
                                        <div class="table-responsive">
                                            <table class="table product-overview table-bordered" id='tbl-products'>
                                                <thead>
                                                    <tr>
                                                        <th width="30" class='text-start'>SN</th>
                                                        <th width="80" class='text-start'>Image</th>
                                                        <th width='370' class='text-start'>Product info
                                                        </th>
                                                        <th width="80" class='text-end'>Unit</th>
                                                        <th width="80" class='text-end'>Quantity</th>
                                                        <th width="100" class='text-end'>Price</th>
                                                        <th width="110" class='text-end'>Discount</th>
                                                        <th width="120" class='text-end'>Taxable Amt.</th>
                                                        <th width='100' class='text-center'>GST</th>
                                                        <th width="120" class='text-end'>Net Amount</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <!-- addRowSelection() -->
                                                        <td colspan="5" class='text-end'>
                                                            <a class="btn btn-success btn-sm pull-left" onclick=''
                                                                data-bs-target='#mdl-find-product'
                                                                data-bs-toggle='modal'>Add
                                                                Products</a>
                                                        </td>
                                                        <th class='text-end'>
                                                            <span>Total</span>
                                                        </th>
                                                        <th class='text-end'>
                                                            <span id='span-total-discount'>0.00</span>
                                                            <input type='hidden' name="total_discount"
                                                                id='total-discount' value='0.00' />
                                                        </th>
                                                        <th class='text-end'>
                                                            <span id='span-total-taxable-amt' class='me-2'>0.00</span>
                                                            <input type="hidden" name='total_taxable_amt'
                                                                id='total-taxable-amt' value='0.00' />
                                                        </th>
                                                        <th class='text-end'>
                                                            <span id='span-total-tax' class='me-2'>0.00</span>
                                                            <input type="hidden" name='total_tax' id='total-tax'
                                                                value='0.00' />
                                                            <!-- <a class="btn btn-sm btn-warning"
                                                                onclick='$(".tax-type").toggleClass("hideRow")'><i
                                                                    class='fa fa-arrows-v'></i></a> -->
                                                        </th>
                                                        <th class='text-end'>
                                                            <span id='span-grand-total'>0.00</span>
                                                            <input type='hidden' name="grand_total" id='grand-total'
                                                                value='0.00' />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8" rowspan='3'>
                                                            <table class='table product-overview table-bordered'
                                                                id='tbl-tax'>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                            <textarea name="notes" id='notes'
                                                                placeholder='Write your notes'
                                                                class='form-control mt-2'></textarea>

                                                        </td>
                                                        <td align="right">Shipping Charges</td>
                                                        <td class='text-end'>
                                                            <input name='shipping_charge' type='number' value=''
                                                                id='shipping-charges' class='form-control text-end'
                                                                onkeyup='calculate_store()' placeholder='0.00' />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-end">
                                                            Round Of
                                                        </td>
                                                        <td class='text-end'>
                                                            <span id='span-round-of-amt'>0.00</span>
                                                            <input type='hidden' name="round_of_amt" id='round-of-amt'
                                                                value='0.00' />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-end fs-24 fw-700">
                                                            Net Total
                                                        </th>
                                                        <th class="fs-20 fw-500 text-end">
                                                            ₹<span id='span-payable-amt'>0.00</span>
                                                            <input type='hidden' name="payable_amt" id='payable-amt'
                                                                value='0.00' />
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <!-- <div class="col-md-10">
                                        <textarea name="details" id='details'
                                            placeholder='Write your delivery terms, taxes & duties ECT.  '
                                            class='form-control mt-2'></textarea>
                                    </div> -->
                                    <div class="col-md-12"><button type='submit' class="mt-2 btn btn-success pull-right"
                                            id='btn-save'>
                                            Submit</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
echo view('sub_modals/transaction/find_product_modal');
?>
<script src="<?=base_url('public/custom/js/transaction/grn.js')?>"></script>
<script src="<?=base_url('public/custom/js/transaction/common.js')?>"></script>
</script>
<script>
$(function() {

    $('#edit-bi').click(function() {
        $('#edit-bi').addClass('d-none');
        $('#edit-bi-ad').addClass('d-none');
        $('#up-bi-gst').removeClass('d-none');
        $('#billi-adre').removeClass('d-none');

    })
    $('#edit-ship').click(function() {
        $('#edit-ship').addClass('d-none');
        $('#edit-ship-ad').addClass('d-none');
        $('#up-ship-gst').removeClass('d-none');
        $('#ship-adre').removeClass('d-none');
    })

    $('#billi-adre').click(function() {
        let bili_adre = $('#billing-address').val();
        let bi_gst_no = $('#gst_no').val();

        $('#bi_bi_adre').html(bili_adre)
        $('#bi_gst_no').html(bi_gst_no)
        $('#up-bi-gst').addClass('d-none');
        $('#edit-bi-ad').removeClass('d-none');
        $('#edit-bi').removeClass('d-none');
        $('#billi-adre').addClass('d-none');
    })


    $('#ship-adre').click(function() {
        let ship_adre = $('#shipping-address').val();
        let ship_gst_no = $('#sec_gst_no ').val();

        $('#sec_ship_adre').html(ship_adre)
        $('#ship_gst_no').html(ship_gst_no)

        $('#up-ship-gst').addClass('d-none');

        $('#edit-ship-ad').removeClass('d-none');
        $('#edit-ship').removeClass('d-none');


        $('#ship-adre').addClass('d-none');
    })

})
</script>