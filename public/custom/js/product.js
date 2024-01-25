function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    let mProduct_table = () => {
        $("#manage_product").DataTable({
            ajax: {
                url: base_url("/product/product/manage_product_list"),
                dataSrc: "details",
                data: function (data) {
                    data.cid = $("#get_categ_id").val();
                },
            },
            columnDefs: [
                { width: "10%", targets: 0 },
                {
                    targets: [0, 3, 4, 5],
                    orderable: false,
                },
                {
                    width: "10%",
                    targets: 5,
                    class: "text-end",
                },
            ],
        });
    };
    mProduct_table();

    $(document).on("change", "#get_categ_id", function () {
        $("#manage_product").DataTable().ajax.reload();
    });


    var btn_save;
    $("#product_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let product = {
            url: base_url("/product/product/add"),
            beforeSend: function () {
                $("#btn-save").attr("disabled", true);
                btn_save = $("#btn-save").html();
                $("#btn-save").html(`<span class="fa-lg"><i class="fa fa-spinner fa-spin"></i></span>`);
            },
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    window.location = base_url("/product/product/manage_product");
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_login);
            },
        };
        $.ajax(product);
    });

    $("#product_updeted_detail").submit(function (e) {
        e.preventDefault();
        let id = $("#pro_id").val();
        var formData = new FormData(this);
        let product = {
            url: base_url("/product/product/edit/" + id),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    window.location = base_url("/product/product/manage_product");
                }
            },
        };
        $.ajax(product);
    });
    $("#product_img").change(function () {
        var file = this.files[0];
        let path = console.log(file);
        if (file) {
            $("#logo").attr("src", URL.createObjectURL(file));
        }
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("uid");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this state",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: true,
            showLoaderOnConfirm: true,
        },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: base_url("/product/product/deleted"),
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
                                    position: "top-right",
                                    loaderBg: "#ff6849",
                                    icon: "success",
                                    hideAfter: 3500,
                                    stack: 6,
                                });
                                $("#manage_product").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $("#hsn-code").change(function () {
        let data = $(this).select2("data")[0];
        let gst = data.subText;
        $("#gst-rate").val(gst);
    });

    $(document).on("click", ".select_hsn", function () {
        let details = $(this).attr("details");
        let hsn_code = $(this).attr("hsn_code");
        let gst_rate = $(this).attr("gst_rate");
        $("#hsn_detail").val(details);
        $("#inpt-hsn-code").val(hsn_code);
        $("#hsn_code").val(hsn_code);
        $("#gst-rate").val(gst_rate);
        $(".bs-example-modal-lg").modal("hide");
    });

    $("#hsn_code_table").DataTable({
        serverSide: true,
        ajax: {
            url: base_url("/product/product/hsn_datatable_json"),
            dataSrc: "details",
        },
        infoCallback: function (settings, start, end, max, total, pre) {
            // console.log(start)
            if (start > total && total != 0) {
                $("#hsn_code_table").DataTable().page("previous").draw("page");
            }
        },
        pageLength: 10,
        columns: [
            { data: 0, orderData: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3, class: "text-right" },
        ],
        order: [
            [0, "asc"]
        ],
        searchDelay: 1000,
    });
});

// Properties rows crud
let next_prpty_row = 1;
$(function () {
    next_prpty_row = countPropertyRows() + 1;
    initializeSelect2();
});

function countPropertyRows() {
    let count = 0;
    $(document)
        .find(".prpty-row")
        .map(function () {
            count++;
        });
    return count;
}

function deleteRow(row_no) {
    let i = 0;
    $(document)
        .find(".prpty-row")
        .map(function () {
            i++;
        });
    if (i <= 1) {
        swal("You can't delete it.", "There is only one row.", "error");
    } else {
        $("#prpty-row-" + row_no).detach();
    }
}

function addRow() {
    let div = `<div class='form-group row prpty-row' id='prpty-row-${next_prpty_row}'>
                <div class='col-md-6'>
                    <select name="label[]" id='prpty-label-${next_prpty_row}' onchange='getValues(${next_prpty_row});' class='prpty-label form-control' style='width:100%'></select>
                </div>
                <div class='col-md-5'>
                    <select name="value[]" id="prpty-val-${next_prpty_row}" class='prpty-val form-control' style='width:100%'></select>
                    </div>
                    <div class='col-md-1'>
                    <a class='btn btn-danger btn-sm' onclick='deleteRow(${next_prpty_row})'><i class='fa fa-close'></i></a>
                </div>
            </div>`;
    $("#div-prpty").append(div);
    initializeSelect2ForOne(`prpty-val-${next_prpty_row}`, "Select a value");
    initializeSelect2ForOne(
        `prpty-label-${next_prpty_row}`,
        "Select a property",
        select2_labels_options,
        "nothing to shoe"
    );
    next_prpty_row++;
}

function initializeSelect2() {
    $(document).find(".prpty-label").select2({
        tags: true,
        placeholder: "Select a property",
    });
    $(document).find(".prpty-val").select2({
        tags: true,
        placeholder: "Select a value",
    });
}

function initializeSelect2ForOne(id, placeholder = "", data = [], val = "") {
    $("#" + id)
        .select2({
            tags: true,
            data: data,
            placeholder: placeholder,
        })
        .select2("val", val);
}

function getValues(RowNo) {
    $.ajax({
        url: base_url("/product/product/get_values"),
        method: "post",
        dataType: "json",
        data: {
            cat_id: $("#category").val(),
            label_id: $("#prpty-label-" + RowNo).val(),
        },
        success: function (res) {
            if (res.status == 1) {
                let options = [];
                res.values.map((item) => {
                    options.push({
                        id: item.id,
                        text: item.value,
                    });
                });
                $("#prpty-val-" + RowNo).empty();
                initializeSelect2ForOne(
                    `prpty-val-${RowNo}`,
                    "Select a value",
                    options
                );
            }
        },
    });
}

function toggleProperties() {
    let cval = $("#category").val();
    if (!cval) {
        $(".prpty-label").attr("disabled", true);
        $(".prpty-val").attr("disabled", true);
    } else {
        $(".prpty-label").attr("disabled", false);
        $(".prpty-val").attr("disabled", false);
    }
}
toggleProperties();

$(function () {
    var previous;
    $("#category")
        .on("focus", function () {
            previous = this.value;
        })
        .change(function () {
            if (previous != this.value) {
                $("#div-prpty").html("");
                addRow();
            }
        });
});