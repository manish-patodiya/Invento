function base_url(uri) {
    return BASE_URL + uri;
}
let next_row_count = 0;
$(function () {
    "use strict";
    $("#print2").click(function () {
        var mode = 'iframe'; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close
        };
        // console.log(options)
        $("section.printableArea").printArea(options);
    });

    function makeTaxTable() {
        let head = '<tr><th class="text-start">Tax</th>';
        TAXES.forEach((ele) => {
            head += `<th>${ele}%</th>`;
        })
        head += '<th>Total</th></tr>';
        let body = '';
        GST_NAMES.forEach((name) => {
            body += `<tr><td class='text-start'>${name}</td>`;
            TAXES.forEach((ele) => {
                body += `<td>0.00</td>`;
            })
            body += '<td>0.00</td></tr>';
        })
        let foot = '<tr><th class="text-start">Total</th>';
        TAXES.forEach((ele) => {
            foot += `<th>0.00</th>`;
        })
        foot += '<th>0.00</th></tr>';
        let table_body = head + body + foot;
        $('#table2 tbody').html(table_body);
    }
    makeTaxTable();

    function addProductRow() {
        $.ajax({
            url: base_url('/invoice/get_invoice_by_id'),
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {

                    let json = res.trans_details[0].taxes_json;
                    calculate_tax(json);

                    res.trans_details.map(function (item) {
                        
                        switch (item.discount_type) {
                            case 1:

                                break;

                            default:
                                break;
                        }
                        console.log();
                        $("#table1 > tbody").append(`
                    <tr class='row-product'>
                    <td><img src="${item.product_img}" class='img-fluid'></td>
                    <td>
                        <div class='row'>
                            <h5 class='col-md-8'>${item.pro_title}</h5>
                            <span class='col-md-4 text-end text-secondary'>HSN: ${item.hsn_code}</span>
                            <p>${item.product_details}</p>
                        </div>
                    </td>
                    <td class='text-center'>${item.title}
                    <td class='text-center'>${item.quantity}
                    </td>
                    <td class='text-end'>
                    ${item.mrp}
                    </td>

                    <td class='text-center'>
                    <div class='d-flex'>
                    <div class='col-md-7'>
                    <input type="hidden" name='gst[]' value='${item.gst_rate}' class='form-control text-end rounded-0 rounded-start'>
                   ${item.gst_rate}` +
                            "%" +
                            `
                    </div>    
                    </div>
                    <div class='position-relative'>
                    <span id='' class='position-absolute end-0'>(${item.tax})</span>
                    </div>
                    </td>
                    <td class='text-end'>                
                    ${item.taxable_amt}
                    </td>
                    <td class='text-end'>
                    <div class='d-flex'>
                    <div class='col-md-7'>
                    <input type="hidden" name='gst[]' value='${item.discount}' class='form-control text-end rounded-0 rounded-start'>
                    ${item.discount}
                    </div>    
                    </div>
                    <div class='position-relative'>
                    <span id='' class='position-absolute end-0'>(${item.discount_amt})</span>
                    </div>
                    </td>
                    <td width="100" class="fw-900 text-end">
                    ${item.total}
                    </td>
                </tr>
                    `)
                    })
                }
            }
        });
    }
    addProductRow();

    function calculate_tax(taxes_json) {
        let taxes = JSON.parse(taxes_json);

        // get state code or UT code from gst numbers
        let inv_gst_no = $('#to_gst_no').val();
        let cur_code = current_branch_gst_no.slice(0, 2);
        let inv_code = inv_gst_no.slice(0, 2);

        let total = 0.00;
        for (var gst in taxes) {
            total += Number(taxes[gst]);

            // initialize all tax variable
            let tax = (taxes[gst] / 2).toFixed(2);
            let tax_total = (total / 2).toFixed(2);
            let percent_total = Number(taxes[gst]).toFixed(2);

            // set tax in table by matching state codes or ut codes
            if (cur_code == inv_code && ($.inArray(cur_code, UT_CODES) == -1)) {

                setTaxInTable(2, Number(gst), tax, tax_total, percent_total);
                setTaxInTable(3, Number(gst), tax, tax_total, percent_total);

            } else if (cur_code == inv_code) {
                setTaxInTable(2, Number(gst), tax, tax_total, percent_total);
                setTaxInTable(4, Number(gst), tax, tax_total, percent_total);

            } else {
                setTaxInTable(5, Number(gst), percent_total, total.toFixed(2), percent_total);
            }
        }

    }

    function setTaxInTable(tr, gst, val, tax_total, percent_total) {
        let gsts = [0].concat(TAXES);
        let td = $.inArray(gst, gsts);
        $(`#table2 tr:nth-child(${tr}) td:nth-child(${td + 1})`).html(val);
        $(`#table2 tr:nth-child(${tr}) td:nth-child(${TAXES.length + 2})`).html(tax_total);
        $(`#table2 tr:nth-child(${GST_NAMES.length + 2}) th:nth-child(${td + 1})`).html(percent_total);
        $(`#table2 tr:nth-child(${GST_NAMES.length + 2}) th:nth-child(${TAXES.length + 2})`).html($('#total-tax').val());
    }
})