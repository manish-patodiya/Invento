function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    $("#countries_table").DataTable({
        ajax: {
            url: base_url("/settings/countries/countries_list"),
            dataSrc: "details",
        },
        columnDefs: [{
            targets: [4],
            orderable: false,
            class: "text-end",
        },],
    });
    var btn_save;
    $("#frm-add-country").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: base_url("/settings/countries/add"),
            beforeSend: function () {
                $("#btn-save").attr("disabled", true);
                btn_save = $("#btn-save").html();
                $("#btn-save").html(`<span class="fa-lg"><i class="fa fa-spinner fa-spin"></i></span>`);
            },
            data: $(this).serialize(),
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
                    $("#phone_code").val("");
                    $("#slug").val("");
                    $("#s_name").val("");
                    $("#coun_name").val("");
                    $("#countries_table").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        });
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("country_id");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this country",
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
                        url: base_url("/settings/countries/delete"),
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
                                $("#countries_table").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $("#frm-edit-country").submit(function (e) {
        e.preventDefault();
        let country = {
            url: base_url("/settings/countries/edit"),
            data: $(this).serialize(),
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#mdl_edit_country").modal("hide");
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "success",
                        hideAfter: 3500,
                        stack: 6,
                    });
                    $("#countries_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(country);
    });

    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("country_id");
        console.log(id);
        let country = {
            url: base_url("/settings/countries/get_country_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    console.log(res.data);
                    $("#country_id").val(res.data.id);
                    $("#e_cname").val(res.data.name);
                    $("#e_s_name").val(res.data.sortname);
                    $("#e_slug").val(res.data.slug);
                    $("#e_phone").val(res.data.phonecode);
                    $("#mdl_edit_country").modal("show");
                }
            },
        };
        $.ajax(country);
    });
});