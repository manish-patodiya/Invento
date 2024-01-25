$(function () {
    var btn_save;
    $('#frm-create-transaction').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url('/transaction/est/add_transaction'),
            beforeSend: function () {
                $("#btn-save").attr("disabled", true);
                btn_save = $("#btn-save").html();
                $("#btn-save").html(`<span class="fa-lg"><i class="fa fa-spinner fa-spin"></i></span>`);
            },
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    showToast(res.msg, '', 'success', 'top-right');
                    setTimeout(() => {
                        window.location = base_url("/transaction/est");
                    }, 10);
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        });
    })

})

$(function () {
    "use strict";
    $(document).on('click', '.cancel', function () {
        let id = $(this).attr('est');
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
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: base_url("/Transaction/est/Cancel"),
                    method: "post",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function (res) {
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
        function (start, end) {
            $('#daterange span').html(start.format(
                'MMMM D, YYYY') +
                ' - ' + end.format('MMMM D, YYYY'))
        }
    );


    $("#EST_detaila").DataTable({

        ajax: {
            url: base_url('/transaction/Est/invo_list'),
            dataSrc: 'details',
            data: function (data) {
                data.frm_date = $('#daterange').data('daterangepicker').startDate.format(
                    'YYYY-MM-DD');
                data.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                data.cust_name = $('#cust_name').val();
                data.tid = $('#trans_type_id').val();
                data.bid = $('#brid').val() ? $('#brid').val() : 0;
            },
            complete: function () {
                $(document).find('.link').popover({
                    trigger: 'manual',
                    placement: 'left',
                    sanitize: false,
                    customClass: "",
                    html: true,
                }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show");
                    var pop_ele = _this;
                    $(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide");
                        }
                    }, 100);
                });
            }

        },
        "columnDefs": [{
            "width": "12%",
            "targets": 0
        },
        {
            'targets': [4, 5],
            'orderable': false,
            "width": "10%",
        },
        {
            'targets': [6, 7],
            'orderable': false,
        },
        {
            'targets': [1, 2],
            "width": "10%",
        },
        {
            'targets': [3],
            "width": "15%",
        },
        {
            'targets': [8],
            'orderable': false,
            "width": "15%",
        },
        {
            'targets': [9],
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
        },],
    })

    $('#daterange').on('apply.daterangepicker', function (ev, picker) {
        $("#EST_detaila").DataTable().ajax.reload();
    });



    $('#cust_name').select2({
        multiple: true,
        placeholder: "Type customer name",
        ajax: {
            url: base_url('/transaction/Est/getbranchaddress'),
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (res) {
                let result = [];
                res.data.map(function (item) {
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

    $("#print").click(function () {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        $("section.printableArea").printArea(options);
    });


    $('.print-Vpo').click(function () {
        let id = $(this).val();
        let tr_tp_id = $(this).attr();
    })


})


function reload_list() {
    $("#EST_detaila").DataTable().ajax.reload();
}