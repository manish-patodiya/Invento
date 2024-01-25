<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.table>tbody>tr>td {
    padding-bottom: 0 !important;
    padding-top: 0 !important;
    vertical-align: top;
}
</style>
<div class="content-wrapper" style="min-height: 298px;">
    <div class="container-full">
        <section class="content invoice printableArea">
            <div class="row invoice-info">
                <div class="row">
                    <style>
                    .table>tbody>tr>td {
                        padding-bottom: 0 !important;
                        padding-top: 0 !important;
                        vertical-align: top;
                    }

                    .d-none {
                        display: none !important;
                    }
                    </style>
                    <div class="col-12 ">
                        <?=$html?>
                    </div>
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
echo view('include/footer/footer.php');
?>

<script src=<?=base_url("/public/assets/vendor_plugins/JqueryPrintArea/demo/jquery.printPage.js")?>></script>
<script>
$(function() {
    "use strict";
    // $("#print2").click(function() {
    //     var mode = 'iframe'; //popup
    //     var close = mode == "popup";
    //     var options = {
    //         mode: mode,
    //         popClose: close
    //     };
    //     .Printer(options)
    // });
    $("#print2").printPage({
        url: '<?=base_url("/invoice/print_invoice/$type/$inv_id")?>',
        attr: "href",
        message: "Your document is being created"
    });
})
</script>