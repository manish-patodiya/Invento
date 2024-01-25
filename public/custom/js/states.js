function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    $("#states_table").DataTable({
        ajax: {
            url: base_url("/settings/states/states_list"),
            dataSrc: "details",
        },
        columnDefs: [{
            targets: [4],
            orderable: false,
            class: "text-end",
        },],
    });

    var btn_save;
    $("#state_details").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let company = {
            url: base_url("/settings/states/add"),
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
                    $("#st_name").val("");
                    $("#st_code").val("");
                    $("#counry_id").val("");
                    $("#states_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(company);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("state_id");
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
                        url: base_url("/settings/states/delete"),
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
                                $("#states_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $("#edit_state").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let states = {
            url: base_url("/settings/states/edit"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#mdl_edit_state").modal("hide");
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#states_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(states);
    });
    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("state_id");
        console.log(id);
        let states = {
            url: base_url("/settings/states/get_state_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    console.log(res.data);
                    $("#state_id").val(res.data.state_id);
                    $("#e_state_name").val(res.data.state_name);
                    $("#e_state_code").val(res.data.state_code);
                    $("#e_country").val(res.data.country_id);
                    $("#mdl_edit_state").modal("show");
                }
            },
        };
        $.ajax(states);
    });
});