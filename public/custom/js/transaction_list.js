function base_url(uri) {
    return BASE_URL + uri;
}
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
            $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
    );


    $("#estimation_detaila").DataTable({

        ajax: {
            url: base_url('/transaction/Invoice_list'),
            dataSrc: 'details',
            data: function(data) {
                data.frm_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                data.to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                data.cust_name = $('#cust_name').val();
                data.tid = $('#trans_type_id').val();
            },

        },
        "columnDefs": [
            { "width": "12%", "targets": 0 },
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
                "width": "15%",
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
        $("#estimation_detaila").DataTable().ajax.reload();
    });

    $('#print').on('click', function() {
        window.print()
    })

    $('#cust_name').select2({
        multiple: true,
        placeholder: " Type customer name",
        ajax: {
            url: base_url('/transaction/getbranchaddress'),
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

})


function reload_list() {
    $("#estimation_detaila").DataTable().ajax.reload();
}