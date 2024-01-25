<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.btn-lg {
    font-size: 1.286rem;
    padding: 6px 32px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto col-md-4">
                    <h4 class="page-title"><i class="fa fa-list"></i> Stock Report</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-body">
                            <?php $Active_role_id = $session->get('user_details')['active_role_id'];?>
                            <?php if ($Active_role_id == 2) {?>
                            <div class="row">
                                <div class='col-md-6'>
                                    <div class="pull-right">
                                        <select name="branch" class="form-control select2" id='branch-id'>
                                            <option value="">Select Branch / Address </option>
                                            <?php foreach ($branches as $v) {?>
                                            <?php $address = $v->address . ', ' . $v->state_name . ', ' . $v->city_name?>
                                            <option value="<?=$v->id?>">
                                                <?=$v->name . "/" . $address?>
                                            </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <div class="btn-group">
                                        <a
                                            class="btn btn-dark btn-outline btn-sm filter d-flex align-items-center">All</a>
                                        <a class="btn btn-dark btn-outline  btn-sm filter" val='low-stock'>Low Stock
                                            <span class='badge badge-warning  text-dark'
                                                id="low-stock-count"></span></a>
                                        <a class="btn btn-dark btn-outline  btn-sm filter" val='out-of-stock'>Out of
                                            Stock <span class='badge badge-danger ' id='out-stock-count'></span></a>
                                    </div>
                                </div>
                            </div>
                            <?php } else {?>
                            <div class='text-center'>
                                <div class="btn-group">
                                    <a class="btn btn-dark btn-outline btn-sm filter d-flex align-items-center">All</a>
                                    <a class="btn btn-dark btn-outline  btn-sm filter" val='low-stock'>Low Stock
                                        <span class='badge badge-warning  text-dark' id="low-stock-count"></span></a>
                                    <a class="btn btn-dark btn-outline  btn-sm filter" val='out-of-stock'>Out of
                                        Stock <span class='badge badge-danger ' id='out-stock-count'></span></a>
                                </div>
                            </div>
                            <?php }?>
                            <input type="checkbox" id="checkbox-out-of-stock" class='stock-checkbox' value='1' />
                            <input type="checkbox" id="checkbox-low-stock" class='stock-checkbox' value='1' />
                            <div class="table-responsive">
                                <table id="stock_report" class="table no-wrap product-order" data-page-size="10">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Photo</th>
                                            <th>Product Detalis</th>
                                            <th>Unit</th>
                                            <th>Stock</th>
                                            <th>Min RO Level</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script>
$(function() {
    var lowStockData = {};

    var dt = $("#stock_report").DataTable({
        serverSide: true,
        ajax: {
            url: base_url("/stock/stockreport"),
            dataSrc: function(json) {
                $('#low-stock-count').html(json.LowStockCount);
                $('#out-stock-count').html(json.OutStockCount);
                lowStockData = json.lowStockData;
                return json.report;
            },
            data: function(data) {
                data.low_stock = $('#checkbox-low-stock').is(':checked') ? 1 : 0;
                data.out_stock = $('#checkbox-out-of-stock').is(':checked') ? 1 : 0;
                data.branch_id = $('#branch-id').val();
            },

        },
        "columnDefs": [{
            "width": "5%",
            'orderable': false,
            "targets": [0, 1, 3],
            "class": 'text-center'
        }],
        "columnDefs": [{
            "width": "10%",
            "targets": [5],
            'orderable': false,
            "class": 'text-center'
        }]
    });

    dt.on("draw", () => {
        for (i in lowStockData) {
            if (lowStockData[i]) {
                $("#stock_report tbody tr").eq(i - 1).css("background-color", "red");
            }
        };
    })


    $('.filter').on('click', function() {
        console.log($(this).attr('val'));
        id = $(this).attr('val');
        $('.stock-checkbox').attr('checked', false);
        $('#checkbox-' + id).attr('checked', true);
        reload_datatable();
    })



    $('#branch-id').select2({
        templateResult: formatSearch,
        // templateSelection: formatSelected,
    }).on('change', function() {

        reload_datatable()
    });

})

function reload_datatable() {
    $("#stock_report").DataTable().ajax.reload();
}

function formatSearch(item) {
    var selectionText = item.text.split("/");
    var $returnString = $('<span><b>' + selectionText[0] + '</b></br>' + selectionText[1] + '</span>');
    return $returnString;
};

function formatSelected(item) {
    var selectionText = item.text.split("/");
    var $returnString = $('<span>' + selectionText[0].substring(0, 21) + '</span>');
    return $returnString;
};
</script>