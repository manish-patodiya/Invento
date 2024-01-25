let next_row_count = 0;
let all_slctd_prodcts = [];
$(function() {
    next_row_count = $(".row-product").length + 1;
    $("select").select2({
        minimumResultsForSearch: -1,
    });

    $("#slct-hsn").select2({
        ajax: {
            url: base_url("/settings/Hsn/getHSNCodes"),
            dataType: "json",
            type: "GET",
            data: function(params) {
                var queryParameters = {
                    term: params.term,
                };
                return queryParameters;
            },
            processResults: function(res) {
                let result = [];
                res.data.map(function(item) {
                    result.push({
                        id: item.hsn_code,
                        text: item.hsn_code,
                        subText: item.gst_rate,
                    });
                });
                return {
                    results: result,
                };
            },
            cache: true,
            delay: 250,
        },
        placeholder: "Select HSN Code",
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(d) {
            return (
                "<span>" +
                d.text +
                '</span><span class="pull-right subtext text-secondary">' +
                (d.subText ? `[${d.subText}%]` : "") +
                "</span>"
            );
        },
        templateSelection: function(d) {
            return d.text;
        },
    });

    $("#mdl-find-product").on("hidden.bs.modal", function() {
        $("#slct-hsn").empty();
        $("#slct-category").val("").trigger("change");
    });

    $(document).on({
            mouseenter: function() {
                $(this).addClass("bg-secondary");
            },
            mouseleave: function() {
                $(this).removeClass("bg-secondary");
            },
        },
        ".div-product"
    );

    $(document).on("click", ".div-product", function() {
        $(this).toggleClass("bg-slct");
        $(this).find("i").toggleClass("d-block");
        $(this).find("img").toggleClass("opacity-40");
        if ($(this).find("input:checkbox").attr("checked")) {
            $(this).find("input:checkbox").attr("checked", false);
        } else {
            $(this).find("input:checkbox").attr("checked", true);
            let product_id = $(this).find("input:checkbox").val();
            if ($.inArray(product_id, all_slctd_prodcts) != -1)
                showToast(
                    "Product already exist.",
                    "Quantity will increased by 1.",
                    "error"
                );
        }
    });

    searchProducts();
});

function get_branch_list(val) {
    if (!val) {
        $("#branch").empty();
        $("#select-sec-branch").empty();
        $(".brnch-dtl").val("");
        return 0;
    }
    $.ajax({
        type: "post",
        url: base_url("/transaction/payment/get_branch_list"),
        data: {
            cid: val,
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                let options = [];
                res.data.map((branch) => {
                    options.push({
                        id: branch.id,
                        text: branch.name,
                        subText: branch.gst_no,
                        address: branch.address,
                        html: `<h5>${branch.name}</h5>
                        <p>${branch.address}</p>`,
                    });
                });
                $("#branch").select2({
                    data: options,
                    placeholder: "Select Brnach",
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    templateResult: function(data) {
                        return data.html;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    },
                });
                $("#select-sec-branch").select2({
                    data: options,
                    placeholder: "Select Brnach",
                    escapeMarkup: function(markup) {
                        return markup;
                    },
                    templateResult: function(data) {
                        return data.html;
                    },
                    templateSelection: function(data) {
                        return data.text;
                    },
                });
            }
        },
    });
}

function set_gst_no_and_address(ele, sec = false) {
    let data = $(ele).select2("data")[0];
    let gst_no = data.subText;
    let adrs = data.address;
    if (!sec) {
        $("#gst_no").val(gst_no);
        $("#bi_gst_no").html(gst_no);
        $("#shipping-address").html(adrs);
        $("#billing-address").html(adrs);
        $("#bi_bi_adre").html(adrs);
        $("#checkbox-show").removeClass("d-none");
        $("#edit-bi").removeClass("d-none");
        $("#edit-bi-ad").removeClass("d-none");
        if (!$("#select-sec-branch").val()) {
            $("#select-sec-branch").val($(ele).val()).trigger("change");
        }
        calculate_tax();
    } else {
        $("#edit-ship").removeClass("d-none");
        $("#edit-ship-ad").removeClass("d-none");
        $("#shipping-address").html("");
        $("#sec_ship_adre").html("");
        $("#sec_gst_no").val(gst_no);
        $("#ship_gst_no").html(gst_no);
        $("#shipping-address").html(adrs);
        $("#sec_ship_adre").html(adrs);
    }
}

$("#basic_checkbox_1").on("click", function() {
    is_chkd = $(this).is(":checked");
    if (!is_chkd) {
        $("#select-sec-branch").attr("disabled", false);
        set_gst_no_and_address($("#select-sec-branch")[0], true);
        $("#edit-ship-ad").removeClass("d-none");
        $("#edit-ship").removeClass("d-none");
        $("#show-edit").removeClass("d-none");
    } else {
        $("#show-edit").addClass("d-none");
        $("#shipping-address").html("");
        $("#select-sec-branch").attr("disabled", true);
        set_gst_no_and_address($("#branch")[0]);
    }
});

function set_shipping_address() {
    let adrs = $("#chng-shipping-address").val();
    $("#shipping-address").html(adrs);
    $("#mdl-change-address").modal("hide");
}

function addProductRow() {
    // loop on selected product
    let slctd_prodcts = [];
    $("input[name=slcted_product]:checked").map(function() {
        if ($.inArray(this.value, all_slctd_prodcts) == -1) {
            all_slctd_prodcts.push(this.value);
        }
        slctd_prodcts.push(this.value);
    });

    // increase qunatity for adding same product
    $("input[name=slcted_product]:checked").map(function() {
        let val = this.value;
        let input = $(`input[name='product_id[]'][value='${val}']`);
        if (input.length) {
            // Remove selected item id from array
            let index = slctd_prodcts.indexOf(val);
            if (index > -1) {
                slctd_prodcts.splice(index, 1); // 2nd parameter means remove one item only
            }
            // increase qty by one
            let qty_input = input.parent(".row-product").find(".quantity");
            cur_qty = Number(qty_input.val());
            new_qty = qty_input.val(cur_qty + 1).trigger("change");
        }
    });

    if (!slctd_prodcts.length) {
        return 0;
    }

    // add new row of product
    $.ajax({
        type: "post",
        url: base_url("/transaction/payment/get_products_by_id"),
        data: {
            product_ids: slctd_prodcts.join(","),
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                console.log(res.data.product);
                res.data.product.map(function(item) {
                    $("#tbl-products > tbody").append(`
                    <tr class='row-product'>
                    <input type="hidden" name='product_id[]' class='product' id='product-${next_row_count}' value='${item.id}'/>
                    <input type="hidden" name='rate[]' class='rate' id='rate-${next_row_count}' value=''/>
                    <td><span class="sn_no"></span></td>
                    <td><img src="${item.product_img}" class='img-fluid'></td>
                    <td>
                        <div class='row'>
                            <h5 class='col-md-8'>${item.title} </h5>
                            <span class='col-md-4 text-end text-secondary'>HSN: ${item.hsn_code_4_digits}</span>
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
                        <input type="number" name='mrp[]' id='mrp-${next_row_count}' class="form-control mrp text-end" placeholder="0.00"
                            onkeyup='calculate_product(${next_row_count})'>
                    </td>
                    <td class='text-end'>
                        <div class='d-flex'>
                            <div class='col-md-9'>
                                <input type="number" name='discount[]' id='discount-${next_row_count}' class="form-control discount col-md-8 text-end rounded-0 rounded-start" placeholder="0.00" onkeyup='calculate_product(${next_row_count})'>
                            </div>    
                            <select name='discount_type[]' id='discount-type-${next_row_count}' class='form-control rounded-0 rounded-end border-start-0 text-center p-1 bg-secondary' onchange='calculate_product(${next_row_count})'>
                                <option value='1'>₹</option>
                                <option value='2'>%</option>
                            </select>
                        </div>
                        <div class='position-relative'>
                            <span id='span-discount-amt-${next_row_count}' class='position-absolute end-0 fw-bold'>0.00</span>
                            <input type="hidden" name='discount_amt[]' id='discount-amt-${next_row_count}' class='discount-amt' value='0.00'>
                        </div>
                    </td>
                    <td class='text-end'>
                        <span id='span-taxable-amt-${next_row_count}'>0.00</span>
                        <input type="hidden" name='taxable_amt[]' id='taxable-amt-${next_row_count}' class='taxable-amt' value='0.00'>
                    </td>
                    <td class='text-center'>
                        <div class='d-flex'>
                            <div class='col-md-8'>
                            <input type="text" readonly  name='gst[]' id='gst-${next_row_count}' value='${item.gst_rate}' class='form-control text-end rounded-0 rounded-start bg-secondary'>
                            </div>    
                            <input class='form-control rounded-0 rounded-end border-start-0 text-center p-0 bg-secondary' value='%'/>
                        </div>
                        <div class='position-relative'>
                            <span id='span-tax-${next_row_count}' class='position-absolute end-0 fw-bold'>0.00</span>
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
                    `);
                    sn_number();
                    unit(item.unit_id, next_row_count);
                    next_row_count++;
                });
            }
        },
    });
}

function unit(unit_id, un_id) {
    let id = unit_id;
    console.log(id);
    $.ajax({
        url: base_url("/transaction/payment/get_base_unit"),
        data: {
            id: id,
        },
        method: "post",
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                let option = "";
                res.data.map(function(item) {
                    option += `<option value='${item.id}'>${item.title}</option>`;
                });
                $("#slct-unit-" + un_id).html(option);
            }
        },
    });
}

function sn_number() {
    let i = 0;
    $(document)
        .find("td>span.sn_no")
        .map(function() {
            $(this).html(++i);
        });
}

$(function() {
    $(document).on("keyup", ".quantity", function() {
        if ($(this).val() == "0") {
            delete_alert(this);
        }
    });
});

function showToast(heading, text, type, direction = "top-left") {
    $.toast({
        heading: heading,
        text: text,
        position: direction,
        loaderBg: "#ff6849",
        icon: type,
        hideAfter: 5000,
        stack: 6,
    });
}

let currentSearch;

function searchProducts() {
    currentSearch = $.ajax({
        type: "GET",
        url: BASE_URL + "/transaction/payment/search_products",
        beforeSend: () => {
            if (currentSearch) {
                currentSearch.abort();
            }
        },
        data: {
            search: $("#srch-product").val(),
            cat_id: $("#slct-category").val(),
            hsn_code: $("#slct-hsn").val(),
            bid: $("#branch-id").val(),
        },
        dataType: "json",
        success: function(res) {
            let products = "";
            res.data.map(function(item) {
                products += `<tr class='div-product pointer'>
                                    <td width='100px'>
                                        <div class='position-relative d-flex align-items-center justify-content-center'>
                                            <input type='checkbox' name='slcted_product' value='${
                                              item.id
                                            }'/>
                                            <img src="${
                                              item.product_img ||
                                              base_url(
                                                "/public/images/product/product-1.png"
                                              )
                                            }" width='100%' style='max-height:80px'>
                                            <i class='position-absolute top-0 fa fa-check fa-3x hideDiv text-success'></i>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>${
                                          item.title.length > 20
                                            ? item.title.slice(0, 20) + "..."
                                            : item.title
                                        }</h5>
                                        <p>${item.product_details}</p>
                                    </td>
                                </tr>
                            `;
            });
            $("#srched-products").html(products);
        },
        delay: 250,
    });
}

function deleteRow(t) {
    var e = t.parentNode.parentNode;
    let val = $(e).find('input[name="product_id[]"]').val();
    let index = all_slctd_prodcts.indexOf(val);
    if (index > -1) {
        all_slctd_prodcts.splice(index, 1); // 2nd parameter means remove one item only
    }
    e.parentNode.removeChild(e);

    removeTaxes([2, 3, 4, 5, 6], true);
    calculate_store();
    sn_number();
}

function delete_alert(ele) {
    swal({
            title: "Are you sure?",
            text: "You can't revert this change!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: true,
            showLoaderOnConfirm: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                deleteRow(ele);
            } else {
                let qty_val = Number($(ele).closest("tr").find(".quantity").val());
                if (qty_val) {
                    $(ele).closest("tr").find(".quantity").val(qty_val);
                } else {
                    $(ele).closest("tr").find(".quantity").val(1);
                }
            }
        }
    );
}

function calculate_product(row_no) {
    // Get information for calculation
    let qty = Number($("#quantity-" + row_no).val()) || 0;
    let mrp = Number($("#mrp-" + row_no).val()) || 0;
    let rate = 0;
    let gst = Number($("#gst-" + row_no).val()) || 0;
    let discount = Number($("#discount-" + row_no).val()) || 0;
    let discount_type = Number($("#discount-type-" + row_no).val());

    // calculate rate of product
    if (invoice_concept == 1) {
        rate = (mrp * 100) / (100 + gst);
    } else if (invoice_concept == 2) {
        rate = mrp;
    }

    $("#rate-" + row_no).val(rate.toFixed(2));

    // calculate tax here
    let tax = ((rate * gst) / 100) * qty;
    $("#span-tax-" + row_no).html(tax.toFixed(2));
    $("#tax-" + row_no).val(tax.toFixed(2));

    // calculate taxable amt
    let taxable_amt = rate * qty;
    $("#span-taxable-amt-" + row_no).html(taxable_amt.toFixed(2));
    $("#taxable-amt-" + row_no).val(taxable_amt.toFixed(2));

    // calculate discount amout
    let discount_amt = 0;
    if (qty && mrp) {
        if (discount_type == 1) {
            discount_amt = discount;
        } else if (discount_type == 2) {
            discount_amt = (rate * qty * discount) / 100;
        }
        $("#span-discount-amt-" + row_no).html(discount_amt.toFixed(2));
        $("#discount-amt-" + row_no).val(discount_amt.toFixed(2));
    } else {
        $("#span-discount-amt-" + row_no).html("0.00");
        $("#discount-amt-" + row_no).val(0.0);
    }
    // total with discount and tax
    let main_total = qty * rate + tax - discount_amt;
    $("#span-total-" + row_no).html(main_total.toFixed(2));
    $("#total-" + row_no).val(main_total.toFixed(2));
    calculate_store();
}

function calculate_store() {
    // calculate grand total
    let grand_total = 0;
    $(".total").map(function() {
        grand_total += Number(this.value) || 0;
    });
    $("#grand-total").val(grand_total.toFixed(2));
    $("#span-grand-total").html(grand_total.toFixed(2));

    // calculate total discount
    let total_discount = 0;
    $(".discount-amt").map(function() {
        total_discount += Number(this.value) || 0;
    });
    $("#total-discount").val(total_discount.toFixed(2));
    $("#span-total-discount").html(total_discount.toFixed(2));

    // Calculate taxable Amount
    // calculate total tax
    let total_taxable_amt = 0.0;
    $(".taxable-amt").map(function() {
        total_taxable_amt += Number(this.value) || 0;
    });
    $("#total-taxable-amt").val(total_taxable_amt.toFixed(2));
    $("#span-total-taxable-amt").html(total_taxable_amt.toFixed(2));

    // calculate total tax
    let total_tax = 0.0;
    $(".tax").map(function() {
        total_tax += Number(this.value) || 0;
    });
    $("#total-tax").val(total_tax.toFixed(2));
    $("#span-total-tax").html(total_tax.toFixed(2));

    // get other charges
    let shipping_charges = Number($("#shipping-charges").val()) || 0;

    let net = grand_total + shipping_charges;

    // calculate round of
    round = Math.round(net) - net;
    $("#span-round-of-amt").html(round.toFixed(2));
    $("#round-of-amt").val(round.toFixed(2));

    // calculate payable amt
    let pay_amt = Math.round(net);
    $("#payable-amt").val(pay_amt.toFixed(2));
    $("#span-payable-amt").html(pay_amt.toFixed(2));
    calculate_tax();
}

let taxes_json = "";

function calculate_tax() {
    let taxes = {
        5: 0.0,
        12: 0.0,
        18: 0.0,
        28: 0.0,
    };
    let gst_arr = [5, 12, 18, 28];
    gst_arr.map(function(gst) {
        $(`input[name="gst[]"][value='${gst}']`)
            .parents("tr")
            .find(".tax")
            .map(function(ele) {
                taxes[gst] = (Number(taxes[gst] || 0) + Number(this.value)).toFixed(2);
            });
    });

    taxes_json = JSON.stringify(taxes);

    $("#taxes-json").val(taxes_json);

    // get state code or UT code from gst numbers
    let inv_gst_no = $("#gst_no").val();
    let cur_code = current_branch_gst_no.slice(0, 2);
    let inv_code = inv_gst_no.slice(0, 2);

    total = 0.0;
    for (var gst in taxes) {
        total += Number(taxes[gst]);

        // initialize all tax variable
        let tax = (taxes[gst] / 2).toFixed(2);
        let tax_total = (total / 2).toFixed(2);
        let percent_total = Number(taxes[gst]).toFixed(2);

        // set tax in table by matching state codes or ut codes
        if (cur_code == inv_code && $.inArray(cur_code, UT_CODES) == -1) {
            removeTaxes([4, 5]);
            setTaxInTable(2, Number(gst), tax, tax_total, percent_total);
            setTaxInTable(3, Number(gst), tax, tax_total, percent_total);
        } else if (cur_code == inv_code) {
            removeTaxes([3, 5]);
            setTaxInTable(2, Number(gst), tax, tax_total, percent_total);
            setTaxInTable(4, Number(gst), tax, tax_total, percent_total);
        } else {
            removeTaxes([2, 3, 4]);
            setTaxInTable(
                5,
                Number(gst),
                percent_total,
                total.toFixed(2),
                percent_total
            );
        }
    }
}

function setTaxInTable(tr, gst, val, tax_total, percent_total) {
    gsts = [0].concat(TAXES);
    let td = $.inArray(gst, gsts);
    $(`#tbl-tax tr:nth-child(${tr}) td:nth-child(${td + 1})`).html(val);
    $(`#tbl-tax tr:nth-child(${tr}) td:nth-child(${TAXES.length + 2})`).html(
        tax_total
    );
    $(
        `#tbl-tax tr:nth-child(${GST_NAMES.length + 2}) th:nth-child(${td + 1})`
    ).html(percent_total);
    $(
        `#tbl-tax tr:nth-child(${GST_NAMES.length + 2}) th:nth-child(${
      TAXES.length + 2
    })`
    ).html($("#total-tax").val());
}

function removeTaxes(arr, last_row = false) {
    arr.map(function(i) {
        $(`#tbl-tax tr:nth-child(${i}) td:not(first)`).not(":first").html("0.00");
    });
    if (last_row) {
        $(`#tbl-tax tr:nth-child(6) th:not(first)`).not(":first").html("0.00");
    }
}

function makeTaxTable() {
    let head = '<tr><th class="text-start">Tax</th>';
    TAXES.forEach((ele) => {
        head += `<th>${ele}%</th>`;
    });
    head += "<th>Total</th></tr>";
    let body = "";
    GST_NAMES.forEach((name) => {
        body += `<tr><td class='text-start'>${name}</td>`;
        TAXES.forEach((ele) => {
            body += `<td>0.00</td>`;
        });
        body += "<td>0.00</td></tr>";
    });
    let foot = '<tr><th class="text-start">Total</th>';
    TAXES.forEach((ele) => {
        foot += `<th>0.00</th>`;
    });
    foot += "<th>0.00</th></tr>";
    table_body = head + body + foot;
    $("#tbl-tax tbody").html(table_body);
}
makeTaxTable();

const month = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
];

function togl_date(inpt = true) {
    if (inpt) {
        $("#chs-date").removeClass("d-none");
        $("#lbl-date").addClass("d-none");
    } else {
        $("#chs-date").addClass("d-none");
        $("#lbl-date").removeClass("d-none");
        lbl_cng_date = $("#chs-date").val();
        var d = new Date(lbl_cng_date);
        $("#lbl-date span").html(
            d.getDate() + " " + month[d.getMonth()] + "  " + d.getFullYear()
        );
    }
}

function setDatepicker(_this) {
    // jQuery class selector
    $("#curr_date")
        .datepicker({
            autoclose: true,
            todayHighlight: true,
            clearBtn: true,
            format: "dd-mmm-yyy",
            todayBtn: "linked",
            startView: 0,
            maxViewMode: 0,
            minViewMode: 0,
        })
        .on("changeDate", function(ev) {
            d = ev.date;
            const month = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ];

            lbl_new_date = $("#lbl-date span").html(
                d.getDate() + " " + month[d.getMonth()] + " " + d.getFullYear()
            );

            var day = ("0" + d.getDate()).slice(-2);
            var m = ("0" + (d.getMonth() + 1)).slice(-2);
            var today = d.getFullYear() + "-" + m + "-" + day;

            $("#chs-date").val(today);
        });
}