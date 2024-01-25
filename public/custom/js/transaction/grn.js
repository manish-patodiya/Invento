$(function () {
    var btn_save;
    $('#frm-create-transaction').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url('/transaction/grn/add_transaction'),
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
                        window.location = base_url("/transaction/grn");
                    }, 2000);
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        });
    })
})


// EST ...............................
$(function () {
    get_estimation_info(grn_inv_id);
})


function get_estimation_info(grn_inv_id) {
    if (grn_inv_id) {
        $.ajax({
            url: base_url('/transaction/grn/get_grn_details_by_id'),
            type: 'post',
            data: {
                grn_inv_id: grn_inv_id,
            },
            dataType: 'json',
            success: function (res) {
                if (res.status == 1) {
                    // grn_details
                    invo_info = res.data.grn_details;
                    // grn_product_details
                    est_pro_info = res.data.grn_product_details;
                    $('#company').val(invo_info.company_adrs_id).trigger('change');
                    // $('#slc-est').val(invo_info.id).trigger('click');
                    let options = [{
                        id: invo_info.branch_adrs_id,
                        text: invo_info.branch_name,
                        subText: invo_info.branch_gst_no,
                        address: invo_info.branch_address,
                        html: `<h5>${invo_info.branch_name}</h5>
                        <p>${invo_info.billing_adrs}</p>`,
                    }];
                    let options2 = [{
                        id: invo_info.sec_branch_adrs_id,
                        text: invo_info.branch_name,
                        subText: invo_info.sec_branch_gst_no,
                        address: invo_info.sec_branch_address,
                        html: `<h5>${invo_info.branch_name}</h5>
                        <p>${invo_info.shipping_adrs}</p>`,
                    }];
                    console.log(options);
                    $('#branch').select2({
                        data: options,
                        placeholder: "Select Billing Address",
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        templateResult: function (data) {
                            return data.html;
                        },
                        templateSelection: function (data) {
                            return data.text;
                        }
                    });
                    $('#select-sec-branch').select2({
                        data: options2,
                        placeholder: "Select Shipping Address",
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                        templateResult: function (data) {
                            return data.html;
                        },
                        templateSelection: function (data) {
                            return data.text;
                        }
                    });

                    // $('#reference-no').val(invo_info.reference_no);
                    // $('#reference-date').val(invo_info.reference_date);
                    $('#notes').val(invo_info.notes);
                    $('#details').val(invo_info.details);
                    $('#select-sec-branch').val(invo_info.sec_branch_adrs_id).trigger('change');
                    $('#branch').val(invo_info.branch_adrs_id).trigger('change');
                    $('#shipping-charges').val(est_pro_info[0].shipping_charges);


                    est_pro_info.map(function (item) {
                        $("#tbl-products > tbody").append(`
                            <tr class='row-product'>
                            <input type="hidden" name='product_id[]' class='product' id='product-${next_row_count}' value='${item.id}'/>
                            <input type="hidden" name='rate[]' class='rate' id='rate-${next_row_count}' value=''/>
                            <td><span class="sn_no"></span></td>
                            <td><img src="${item.product_img}" class='img-fluid'></td>
                            <td>
                                <div class='row'>
                                    <h5 class='col-md-8'>${item.product_name} </h5>
                                    <span class='col-md-4 text-end text-secondary'>HSN: ${item.hsn_code}</span>
                                    <p>${item.product_details}</p>
                                </div>
                            </td>
                            <td class='text-end'>
                            <select  name='unit[]' class="form-control col-md-8  sel_unit" id='slct-unit-${next_row_count}'>
                                         </select>

                            </td>
                            <td class='text-end'>
                                <input type="number" name='quantity[]' class="quantity form-control text-end" id='quantity-${next_row_count}'
                                    placeholder="0" onkeyup='calculate_product(${next_row_count})' onchange='calculate_product(${next_row_count})' value='1'  onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </td>
                            <td class='text-end'>
                                <input type="number" name='mrp[]' id='mrp-${next_row_count}' value='${item.mrp}'class="form-control mrp text-end" placeholder="0.00"
                                    onkeyup='calculate_product(${next_row_count})'>
                            </td>
                            <td class='text-end'>
                                <div class='d-flex'>
                                    <div class='col-md-9'>
                                        <input type="number" name='discount[]' value='${item.discount}'id='discount-${next_row_count}' class="form-control discount col-md-8 text-end rounded-0 rounded-start" placeholder="0.00" onkeyup='calculate_product(${next_row_count})'>
                                    </div>
                                    <select name='discount_type[]' id='discount-type-${next_row_count}' class='form-control rounded-0 rounded-end border-start-0 text-center p-1 bg-secondary' onchange='calculate_product(${next_row_count})'>
                                        <option value='1' ${item.discount_type == 1 ? 'selected' : false}>₹</option>
                                        <option value='2'  ${item.discount_type == 2 ? 'selected' : false}>%</option>
                                    </select>
                                </div>
                                <div class='position-relative'>
                                    <span id='span-discount-amt-${next_row_count}' class='position-absolute end-0 fw-bold'>0.00</span>
                                    <input type="hidden" name='discount_amt[]' id='discount-amt-${next_row_count}' class='discount-amt' value='0.00'>
                                </div>
                            </td>
                            <td class='text-end'>
                                <span id='span-taxable-amt-${next_row_count}'>${item.taxable_amt}</span>
                                <input type="hidden" name='taxable_amt[]' value='${item.taxable_amt}'id='taxable-amt-${next_row_count}' class='taxable-amt' value='0.00'>
                            </td>
                            <td class='text-center'>
                                <div class='d-flex'>
                                    <div class='col-md-8'>
                                    <input type="text" readonly  name='gst[]' id='gst-${next_row_count}' value='${item.gst_rate}' class='form-control text-end rounded-0 rounded-start bg-secondary'>
                                    </div>
                                    <input class='form-control rounded-0 rounded-end border-start-0 text-center p-0 bg-secondary' value='%'/>
                                </div>
                                <div class='position-relative'>
                                    <span id='span-tax-${next_row_count}' class='position-absolute end-0 fw-bold'>${item.tax}</span>
                                    <input type="hidden" name='tax[]' id='tax-${next_row_count}' class='tax' value='0.00'>
                                </div>

                            </td>
                            <td width="100" class="fw-900 text-end">
                                <span id='span-total-${next_row_count}'>0.00</span>
                                <input type='hidden' name="total[]" id='total-${next_row_count}' class='total' value='0.00'/>
                            </td>
                            <td class='w-0'>
                                <a class='btn btn-danger btn-sm' onclick='delete_alert(this)'>
                                    <i class='fa fa-trash'></i></a>
                            </td>
                        </tr>
                            `)
                        sn_number()
                        unit(item.unit_id, next_row_count)
                        calculate_product(next_row_count)
                        all_slctd_prodcts.push(item.id);
                        next_row_count++;
                    })
                }
            }
        })

        disabled()
    }

    function disabled() {
        $('select').attr('readonly', true);
        $('input').attr('readonly', true);
        $('textarea').attr('readonly', true);
        $('.mrp').attr('readonly', true);
        $('.quantity').attr('readonly', true);
        $('#reference-no').attr('readonly', false);
        $('#reference-date').attr('readonly', false);
    }

    $('#slc-est').on('change', function () {
        grn_inv_id = $(this).val();
        console.log(grn_inv_id);
        window.location = base_url("/transaction/grn/create_grn/" + grn_inv_id);

    })

}