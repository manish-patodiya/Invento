function base_url(uri) {
    return BASE_URL + uri;
}

$(function() {
    "use strict";
    $("#na_datatable").DataTable({
        stateSave: true,
        ajax: {
            url: base_url("/templates/templatesList"),
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

    $("#templates_detail").submit(function(e) {
        e.preventDefault();
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        var formData = new FormData(this);
        let templates = {
            url: base_url("/templates/add"),
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
                    window.location = base_url("/templates");
                }
            },
        };
        $.ajax(templates);
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
                        url: base_url("/templates/deleted"),
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
                                $("#na_datatable").DataTable().ajax.reload();
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $("#edit_templates_detail").submit(function(e) {
        e.preventDefault();
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        let id = $("#temp_id").val();
        var formData = new FormData(this);
        let templates = {
            url: base_url("/templates/edit/" + id),
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
                    window.location = base_url("/templates");
                }
            },
        };
        $.ajax(templates);
    });
});