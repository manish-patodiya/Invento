function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    $("#hsn_details_table").DataTable({
        serverSide: true,
        ajax: {
            url: base_url("/settings/hsn/datatable_json"),
            dataSrc: "details",
        },
        infoCallback: function (settings, start, end, max, total, pre) {
            // console.log(start)
            if (start > total && total != 0) {
                $("#hsn_details_table").DataTable().page("previous").draw("page");
            }
        },
        pageLength: 10,
        columns: [
            { data: 0, orderData: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
        ],
        order: [
            [0, "asc"]
        ],
        columnDefs: [{
            targets: [5],
            orderable: false,
            class: "text-end",
        },],
        searchDelay: 1000,
    });

    $(document).on("click", ".add", function () {
        $("#mdl_add_hsn").modal("show");
    });

    var btn_save;
    $("#hsn_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let hsn = {
            url: base_url("/settings/hsn/add"),
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
                    $("#mdl_add_hsn").modal("hide");
                    $("#detail").val("");
                    $("#hsn_code").val("");
                    $("#hsn_code_4_digits").val("");
                    $("#gst_rate").val("");
                    $("#hsn_details_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(hsn);
    });

    $("#edit_hsn_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let hsn = {
            url: base_url("/settings/hsn/edit"),
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
                        hideAfter: 3000,
                        stack: 6,
                    });
                    $("#mdl_edit_hsn").modal("hide");
                    $("#hsn_details_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(hsn);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("hsn_id");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data",
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
                        url: base_url("/settings/hsn/delete_hsn"),
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
                                    hideAfter: 3000,
                                    stack: 6,
                                });
                                $("#hsn_details_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("hsn_id");
        console.log(id);
        let hsn = {
            url: base_url("/settings/hsn/get_hsn_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    console.log(res.data);
                    $("#e_hsn_id").val(res.data.id);
                    $("#e_detail").val(res.data.details);
                    $("#e_hsn_code").val(res.data.hsn_code);
                    $("#e_hsn_code_4_digits").val(res.data.hsn_code_4_digits);
                    $("#e_gst_rate").val(res.data.gst_rate);
                    $("#mdl_edit_hsn").modal("show");
                }
            },
        };
        $.ajax(hsn);
    });

    let btn_upld;
    $("#frm-bulk-upload-hsn").submit(function (event) {
        event.preventDefault();
        let form_data = new FormData(this);
        $.ajax({
            type: "POST",
            contentType: false,
            processData: false,
            dataType: "json",
            url: base_url("/settings/hsn/upload_csv"),
            data: form_data,
            beforeSend: function () {
                $("#bulk-upld-errors")
                    .html("Please do not refresh the page")
                    .addClass("alert-warning")
                    .show();
                btn_upld = $("#btn-upld").html();
                $("#btn-upld")
                    .html(
                        `<span class="fa-lg"><i class="fa fa-spinner fa-spin"></i></span>`
                    )
                    .attr("disabled", true);
            },
            success: function (res) {
                $("#bulk-upld-errors").html("").hide();
                if (res.status == 1) {
                    $.toast({
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#frm-bulk-upload").trigger("reset");
                } else {
                    if (res.errors) {
                        for (const key in res.errors) {
                            $("#bulk-upld-errors").append(`<li>${res.errors[key]}</li>`);
                        }
                        $("#bulk-upld-errors").removeClass("alert-warning").show();
                    } else {
                        $.toast({
                            text: res.msg,
                            position: "top-right",
                            loaderBg: "#ff6849",
                            icon: "error",
                            hideAfter: 3500,
                            stack: 6,
                        });
                    }
                }
            },
            complete: function () {
                $("#btn-upld").html(btn_upld).attr("disabled", false);
            },
            error: function () {
                $("#bulk-upld-errors").html("").hide();
            },
        });
    });
});