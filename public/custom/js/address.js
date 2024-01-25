function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    $("#address_table").DataTable({
        ajax: {
            url: base_url("/address/addressList"),
            dataSrc: "details",
        },
        columnDefs: [
            { width: "10%", targets: 0 },
            {
                targets: [4],
                orderable: false,
                width: "28%",
                class: "text-end",
            },
        ],
    });

    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("address");
        console.log(id);
        let unit = {
            url: base_url("/address/get_address_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    console.log(res.data.title);
                    $("#e_address_id").val(res.data.id);
                    $("#e_title").val(res.data.name);
                    $("#e_email").val(res.data.email);
                    $("#e_phone").val(res.data.mobile);
                    $("#e_website_url").val(res.data.website_url);
                    $("#mdl_edit_address").modal("show");
                }
            },
        };
        $.ajax(unit);
    });

    $("#edit_form").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/address/update"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#mdl_edit_address").modal("hide");
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#address_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(unit);
    });

    var btn_save;
    $("#address_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/address/add"),
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
                    $("#website_url").val("");
                    $("#mobile_no").val("");
                    $("#email").val("");
                    $("#title").val("");
                    $("#address").val("");
                    $("#address_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html('');
            },
        };
        $.ajax(unit);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("address");
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
                        url: base_url("/address/deleted"),
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
                                $("#address_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    //view branch add form
    $("#branch_address_book_table").DataTable({
        ajax: {
            url: base_url("/address/view_branches_List"),
            dataSrc: "details",
            data: function name(p) {
                p.address_id = $("#address_id").val();
            },
        },
        columnDefs: [
            { width: "10%", targets: 0 },
            {
                targets: [4],
                orderable: false,
                width: "28%",
            },
        ],
    });


    var btn_save;
    $("#branch_address_book_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/address/view_branches_add"),
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
                    $("#b_name").val("");
                    $("#email").val("");
                    $("#mobile_no").val("");
                    $("#gst_no").val("");
                    $("#address").val("");
                    $("#branch_address_book_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html('');
            },
        };
        $.ajax(unit);
    });

    $(document).on("click", ".sup_update_view_branch", function () {
        let id = $(this).attr("address");
        console.log(id);
        let unit = {
            url: base_url("/address/get_view_branch_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    console.log(res.data);
                    $("#e_address_id").val(res.data.id);
                    $("#e_parent_id").val(res.data.parent_id);
                    $("#e_b_name").val(res.data.name);
                    $("#e_email").val(res.data.email);
                    $("#e_mobile_no").val(res.data.mobile);
                    $("#e_gst_no").val(res.data.gst_no);
                    $("#e_address").val(res.data.address);
                    $("#mdl_edit_view_branch").modal("show");
                }
            },
        };
        $.ajax(unit);
    });

    $("#edit_form_view_branch").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/address/view_branch_update"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#mdl_edit_view_branch").modal("hide");
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#branch_address_book_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(unit);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("address");
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
                        url: base_url("/address/view_branch_deleted"),
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
                                $("#branch_address_book_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });
});