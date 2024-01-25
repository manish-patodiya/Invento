<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
// $prefix = isset($trans_type_info->prefix) && $trans_type_info->prefix ? "$trans_type_info->prefix-$trans_type_info->start_no" : $trans_type_info->start_no;
$gst_no = $session->get('user_details')['gst_no'] ?: 0;
$invoice_concept = $session->get('user_details')['branch_concept_id'] ?: $session->get('user_details')['company_concept_id'];
$trans_type = isset($trans_type_info->id) ? $trans_type_info->id : "";
?>
<style>
#table2 tbody tr td {
    /* width: 77px; */
    padding:
        2px;
}

#table2 tbody tr th {
    /* width: 77px; */
    padding:
        2px;
}
</style>
<script>
const UT_CODES = JSON.parse('<?=json_encode(UT_CODES)?>');
const TAXES = JSON.parse('<?=json_encode(TAXES)?>');
const GST_NAMES = JSON.parse('<?=json_encode(GST_NAMES)?>');
let current_branch_gst_no = '<?=$gst_no?>';
let invoice_concept = <?=$invoice_concept?>;
</script>
<div class="content-wrapper" style="min-height: 298px;">
    <div class="container-full">
        <section class="content invoice printableArea">
            <div class="row invoice-info">
                <!-- <div class=""> -->
                <div class="" style="float: left;
  width: 30%;
  height: 100px;">

                    <strong class="">Invoice</strong>
                    <div class="">
                        <div class="" style="float: left;width:25%;height: 15px;">Invoice</div>
                        <div class=""><b><?=$trans_details[0]->invoice_id?></b>
                        </div>
                    </div>
                    <div class="">
                        <div class="" style="float: left;width:25%;height: 15px;">Billed On</div>
                        <div class=""><b><?=$trans_details[0]->reference_date?></b></div>
                    </div>
                    <!-- </div> -->
                </div>
                <div class="text-end" style="float: left;
  width: 70%;
  height: 100px;">
                    <address>
                        <strong class="text-blue fs-24"><?=$trans_details[0]->name?></strong><br>
                        <strong class="d-inline"><?=$trans_details[0]->address?>,
                            <?=$trans_details[0]->state_name?>, <?=$trans_details[0]->city_name?></strong><br>
                        <strong>Phone: <?=$trans_details[0]->phone?> &nbsp;&nbsp;&nbsp;&nbsp; Email:
                            <?=$trans_details[0]->email?></strong>
                    </address>
                </div>
                <!-- </div> -->
                <div class="row">
                    <div class="" style="float: left;width:50%;height: 100px;">
                        <strong>Bill To</strong>
                        <address>
                            <strong class="text-blue fs-24"><?=$trans_details[0]->branch_name?></strong><br>
                            <?=$trans_details[0]->branch_address?><br>
                            <strong>Phone: <?=$trans_details[0]->branch_mobile?> &nbsp;&nbsp;&nbsp;&nbsp; Email:
                                <?=$trans_details[0]->branch_email?></strong>
                        </address>
                    </div>
                    <div class="text-end" style="float: left;
  width: 50%;
  height: 100px;">
                        <strong>Ship To</strong>
                        <address>
                            <strong class="text-blue fs-24"><?=$trans_details[0]->sec_branch_name?></strong><br>
                            <?=$trans_details[0]->sec_branch_address?><br>
                            <strong>Phone: <?=$trans_details[0]->sec_branch_mobile?> &nbsp;&nbsp;&nbsp;&nbsp; Email:
                                <?=$trans_details[0]->sec_branch_email?></strong>
                        </address>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-12 table-responsive">
                    <input type="hidden" id="to_gst_no" value="<?=$trans_details[0]->to_gst_no?>">
                    <input type="hidden" id="total-tax" value="<?=$trans_details[0]->total_gst_tax?>">
                    <table class="table product-overview table-bordered" id='table1'>
                        <thead>
                            <tr>
                                <th width="80" class='text-start'>Image</th>
                                <th width='370' class='text-start'>Product Name
                                </th>
                                <th width="40" class='text-center'>Unit.</th>
                                <th width="40" class='text-center'>Qty.</th>
                                <th width="100" class='text-end'>MRP</th>
                                <th width='100' class='text-center'>GST</th>
                                <th width="120" class='text-end'>Taxable Amt.</th>
                                <th width="110" class='text-end'>Discount</th>
                                <th width="120" class='text-end'>Net Total</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <!-- addRowSelection() -->
                                <td colspan="4" class='text-end'>
                                </td>
                                <th class='text-end'>
                                    <span>Total</span>
                                </th>
                                <th class='text-end'><?=$trans_details[0]->total_gst_tax?>
                                </th>
                                <th class='text-end'>
                                    <span id='span-total-tax' class='me-2'></span>
                                    <input type="hidden" name='total_tax' id='total-tax' value='0.00' />
                                </th>
                                <th class='text-end'>
                                    <?=$trans_details[0]->total_discount?>
                                </th>
                                <th class='text-end'>
                                    <span id='span-grand-total'><?=$trans_details[0]->grand_total?></span>

                                </th>
                            </tr>
                            <tr>
                                <td colspan="6" rowspan='3'>
                                    <table class='table product-overview table-bordered' id='table2'>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </td>
                                <td colspan="1" align="right">Shipping Charges</td>
                                <td class='text-end'><?=$trans_details[0]->shipping_charges?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" class="text-end">
                                    Round Of
                                </td>
                                <td class='text-end'>
                                    <span id='span-round-of-amt'></span>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="1  " class="text-end fs-24 fw-700">
                                    Net Total
                                </th>
                                <th class="fs-20 fw-500 text-end">
                                    <?=$trans_details[0]->payable_amt?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <div class="row no-print">
                <div class="col-12">
                    <button id="print2" class="btn btn-warning" type="button"> <span><i class="fa fa-print"></i>
                            Print</span> </button>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('sub_modals/product_base/hsn_code_datatable.php');
echo view('sub_modals/product_base/edit_category_modal.php');
echo view('include/footer/footer.php');
?>
<script src=<?=base_url("/theme/html/assets/vendor_plugins/JqueryPrintArea/demo/jquery.PrintArea.js")?>></script>
<script>
$("#print2").click(function() {
    var mode = 'iframe';
    var close = mode == "popup";
    var options = {
        mode: mode,
        popClose: close
    };
    $("section.printableArea").printArea(options);
});
</script>
<script src="<?=base_url('public/custom/js/invoice.js')?>"></script>