function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    $("#categories_table").DataTable({
        ajax: {
            url: base_url("/product/categories/categoriesList"),
            dataSrc: "details",
        },
        columnDefs: [
            { width: "10%", targets: 0 },
            {
                targets: [2],
                orderable: false,
                class: "text-end",
            },
        ],
        order: [],
    });

    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("cate_id");
        let unit = {
            url: base_url("/product/categories/get_categories_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#e_category_id").val(res.data.id);
                    $("#e_ctegory").val(res.data.category_name);
                    $("#mdl_edit_category").modal("show");
                }
            },
        };
        $.ajax(unit);
    });

    $("#edit_form").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/product/categories/edit"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $(".modal").modal("hide");
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#categories_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(unit);
    });

    var btn_save;
    $("#categories_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/product/categories/add"),
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
                    $("#ctegory").val("");
                    $("#categories_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(unit);
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
                        url: base_url("/product/categories/deleted"),
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
                                $("#categories_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    // csv
    $("#btn-add-csv").click(function () {
        $("#mdl-add-csv").modal("show");
    });

    $("#frm-add-csv").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var upload = {
            url: base_url("/product/categories/uploadCSV"),
            data: formData,
            dataType: "json",
            method: "post",
            processData: false,
            contentType: false,
            success: function (res) {
                if ((res.status = 1)) {
                    $("#mdl-add-csv").modal("hide");
                    $(".modal-backdrop").remove();
                    $("#success-msg").html(res.msg);
                    $("#success-msg").show();
                    $("#categories_table").DataTable().ajax.reload(null, false);
                    $("#csv_upload_file").val("");
                    setTimeout(function () {
                        $("#success-msg").hide();
                    }, 1000);
                }
            },
        };
        $.ajax(upload);
    });
});