<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>

<link rel="stylesheet" href="<?=base_url('public/assets/vendor_components/jquery-ui/jquery-ui.css')?>">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
.btn-widht {
    font-weight: 300;
    line-height: 1.3;
    font-size: 116%;
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 4px 15px;
    text-align: center;
    display: inline-block;
    margin: 2px 2px;
    cursor: pointer;
    width: 130px;

}

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
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; Customer purchase order</h4>
                </div>
            </div>
        </div>
        <section class="content">
            <input type="hidden" name='trans_type_id' id='trans_type_id' value='2'>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="COP_detaila" class="table" width="100%">
                        <thead>
                            <div class="row mb-2" id='srch-filter'>
                                <div class="col-md-4 form-group">
                                    <div class="input-group">
                                        <select name="search" class='form-control' id="cust_name"
                                            onchange='reload_list()'>
                                        </select>
                                        <span class='search'><i class="fa fa-search"></i></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                                <div class="col-md-4">
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
                                <?php } else {?>
                                <div class="col-md-4">
                                    <?php if (check_method_access('cpo', 'add', true)) {?>
                                    <a href="<?php echo base_url("transaction/cpo/create_cpo") ?>"
                                        class="btn btn-sm  btn-info pull-right">
                                        Create CPO
                                    </a>
                                    <?php }?>
                                </div>
                                <?php }?>
                            </div>
                            <tr>
                                <th width='20%' scope="col">Ref No. / Date</th>
                                <th width='20%' scope="col">CPO No.</th>
                                <th width='20%' scope="col">Created By</th>
                                <th width='18%' scope="col">Customer</th>
                                <th width='10%' scope="col">Taxable Amt</th>
                                <th width='10%' scope="col">GST Total</th>
                                <th width='10%' scope="col">Net Total</th>
                                <th width='10%' scope="col">Status</th>
                                <!-- <th width='10%' scope="col">Generate</th> -->
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

<script src="<?=base_url('public/assets/vendor_components/jquery-ui/jquery-ui.js')?>">
</script>

<script>
$(function() {
    $('.select2').select2({
        templateResult: formatSearch,
    }).on('change', function() {
        $('#div-show').show();
        let id = $(this).val();
        // let url = base_url("/transaction/est/create_estimation/") + id;
        // $('#branch-id').attr('href', url);
        reload_list();
    })

})

function formatSearch(item) {
    var selectionText = item.text.split("/");
    var $returnString = $('<span><b>' + selectionText[0] + '</b></br>' + selectionText[1] + '</span>');
    return $returnString;
};
</script>
<script>
$(function() {
    "use strict";


    $('#daterange').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
            },
            startDate: moment(),
            endDate: moment()
        },
        function(start, end) {
            $('#daterange span').html(start.format('MMMM D, YYYY') +
                ' - ' + end.format('MMMM D, YYYY'))
        }
    );


    $("#COP_detaila").DataTable({

        ajax: {
            url: base_url('/transaction/cpo/CPO_list'),
            dataSrc: 'details',
            data: function(data) {
                data.frm_date = $('#daterange').data('daterangepicker').startDate.format(
                    'YYYY-MM-DD');
                data.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                data.cust_name = $('#cust_name').val();
                data.tid = $('#trans_type_id').val();
                data.bid = $('#brid').val() ? $('#brid').val() : 0;
            },
            complete: function() {
                $(document).find('.link').popover({
                    trigger: 'manual',
                    placement: 'right',
                    sanitize: false,
                    customClass: "",
                    html: true,
                }).on("mouseenter", function() {
                    var _this = this;
                    $(this).popover("show");
                    var pop_ele = _this;
                    $(".popover").on("mouseleave", function() {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function() {
                    var _this = this;
                    setTimeout(function() {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide");
                        }
                    }, 100);
                });
            },

        },
        "columnDefs": [{
                "width": "10%",
                "targets": 0
            },
            {
                'targets': [4, 5],
                'orderable': false,
                "width": "8%",
                'class': 'text-end'
            },
            {
                'targets': [6, 7],
                'orderable': false,
                "width": "8%",
                'class': 'text-center'
            },
            {
                'targets': [1],
                'class': 'text-center',
                "width": "8%",
            },
            // {
            //     'targets': [8],
            //     'orderable': false,
            //     'class': 'text-center',
            //     "width": "10%",
            // },
            {
                'targets': [2, 3],
                'orderable': false,
                'class': 'text-center',
                "width": "10%",
            },
            {
                'targets': [8],
                'class': 'text-end',
                'orderable': false,
                "width": "10%",
            }
        ],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copy',
            footer: false,
            className: 'btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }, {
            extend: 'csv',
            footer: false,
            className: 'btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }, {
            extend: 'excel',
            footer: false,
            className: 'btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }, {
            extend: 'pdf',
            footer: false,
            className: 'btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }, {
            extend: 'print',
            footer: false,
            className: 'btn-sm',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6]
            }
        }, ],
    })

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $("#COP_detaila").DataTable().ajax.reload();
    });



    $('#cust_name').select2({
        multiple: true,
        placeholder: " Type customer name",
        ajax: {
            url: base_url('/transaction/cpo/getbranchaddress'),
            dataType: 'json',
            type: "GET",
            data: function(params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function(res) {
                let result = [];
                res.data.map(function(item) {
                    result.push({
                        id: item.id,
                        text: item.name,

                    });
                })
                return {
                    results: result
                };
            },
            cache: true,
            delay: 250,
        },
    })

    $("#print").click(function() {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("section.printableArea").printArea(options);
    });


    $('.print-Vpo').click(function() {
        let id = $(this).val();
        let tr_tp_id = $(this).attr();
    })


    $(document).on('click', '.cancel', function() {
        let id = $(this).attr('cpo');
        console.log(id);
        swal({
            title: "Are you sure?",
            text: "You will not be able to revert this change.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, cancel it!",
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: base_url("/transaction/cpo/Cancel"),
                    method: "post",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status == 1) {
                            $.toast({
                                // heading: 'Welcome to my Deposito Admin',
                                text: res.msg,
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'success',
                                hideAfter: 3500,
                                stack: 6
                            });
                            reload_list();
                        } else {
                            swal("Deletion Failed!", res.msg, "error");
                        }
                    }
                })
            }
        });
    });

})


function reload_list() {
    $("#COP_detaila").DataTable().ajax.reload();
}
</script>