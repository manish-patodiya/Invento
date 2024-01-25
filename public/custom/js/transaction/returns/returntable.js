function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
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
        function (start, end) {
            $('#daterange span').html(start.format('MMMM D, YYYY') +
                ' - ' + end.format('MMMM D, YYYY'))
        }
    );


    $("#return_details").DataTable({

        ajax: {
            url: base_url('/transaction/returns/Return_list'),
            dataSrc: 'details',
            data: function (data) {
                data.frm_date = $('#daterange').data('daterangepicker').startDate.format(
                    'YYYY-MM-DD');
                data.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                data.cust_name = $('#cust_name').val();
                data.tid = $('#trans_type_id').val();
                data.bid = $('#brid').val() ? $('#brid').val() : 0;
            },

        },
        "columnDefs": [{
            "width": "12%",
            "targets": 0
        },
        {
            'targets': [4, 5],
            'orderable': false,
            "width": "10%",
            'class': 'text-end'
        },
        {
            'targets': [6, 7],
            'orderable': false,
            'class': 'text-center'
        },
        {
            'targets': [1, 2],
            'class': 'text-center',
            "width": "10%",
        },
        {
            'targets': [3],
            'class': 'text-center',
            "width": "15%",
        },
        {
            'targets': [8],
            'class': 'text-end',
            'orderable': false,
            "width": "5%",
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
        $("#return_details").DataTable().ajax.reload();
    });



    $('#cust_name').select2({
        multiple: true,
        placeholder: " Type customer name",
        ajax: {
            url: base_url('/transaction/returns/getbranchaddress'),
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

    $(document).on('click', '.cancel', function () {
        let id = $(this).attr('db');
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
                    url: base_url("/returnnote/debitnotes/Cancel"),
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

})


