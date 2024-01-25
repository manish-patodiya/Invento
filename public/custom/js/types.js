function base_url(uri) {
    return BASE_URL + uri;
}
$(function() {
    "use strict";

    $("#type_table").DataTable({
        ajax: {
            url: base_url("/settings/type/typeList"),
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
    });

    $(document).on("click", ".sup_update", function() {
        let id = $(this).attr("tid");
        console.log(id);
        let unit = {
            url: base_url("/settings/type/get_type_id"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    console.log(res.data.title);
                    $("#e_type_id").val(res.data.id);
                    $("#e_title").val(res.data.title);
                    $("#mdl_edit_type").modal("show");
                }
            },
        };
        $.ajax(unit);
    });

    $("#edit_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let type = {
            url: base_url("/settings/type/update"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function(res) {
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
                    $("#mdl_edit_type").modal("hide");
                    $("#type_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(type);
    });
    $("#type_detail").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let type = {
            url: base_url("/settings/type/add"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function(res) {
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
                    $("#type_table").DataTable().ajax.reload();
                }
            },
        };
        $.ajax(type);
    });

    $(document).on("click", ".sup_delete", function() {
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
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: base_url("/settings/type/deleted"),
                        method: "post",
                        data: {
                            id: id,
                        },
                        dataType: "json",
                        success: function(res) {
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
                                $("#type_table").DataTable().ajax.reload();
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