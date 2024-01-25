function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    $("#na_datatable").DataTable({
        ajax: {
            url: base_url("/branches/branches_List"),
            dataSrc: "details",
            method: "post",
            data: function (data) {
                data.id = $("#c_name").val();
            },
        },
        columnDefs: [{
            targets: [4],
            orderable: false,
            width: "13%",
            class: "text-end",
        },],
    });

    $("#c_name").change(function (e) {
        $("#na_datatable").DataTable().ajax.reload();
    });

    var btn_save;
    $("#branches_detail").submit(function (e) {
        e.preventDefault();
        $pass = $('input[name="password"]').val().length;
        if ($pass >= 8) {
            var formData = new FormData(this);
            let branches = {
                url: base_url("/branches/add"),
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
                        setTimeout(() => {
                            window.location = base_url("/branches");
                        }, 3500);
                    } else {
                        $.toast({
                            // heading: 'Welcome to my Deposito Admin',
                            text: res.msg,
                            position: "top-right",
                            loaderBg: "#ff6849",
                            icon: "error",
                            hideAfter: 5000,
                            stack: 6,
                        });
                    }
                },
                complete: function () {
                    $("#btn-save").attr("disabled", false).html(btn_save);
                },
            };
            $.ajax(branches);
        }
    });

    $("#bran_updeted_detail").submit(function (e) {
        e.preventDefault();
        let id = $("#branches_id").val();
        let form_data = $("#bran_updeted_detail").serializeArray();
        var formData = new FormData(this);
        let branches = {
            url: base_url("/branches/edit/" + id),
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
                    setTimeout(() => {
                        window.location = base_url("/branches");
                    }, 3500);
                } else {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: "top-right",
                        loaderBg: "#ff6849",
                        icon: "error",
                        hideAfter: 5000,
                        stack: 6,
                    });
                }
            },
        };
        $.ajax(branches);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("branch_id");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this branch",
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
                        url: base_url("/branches/deleted_branches"),
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

    $(document).on("change", "#b-state", function () {
        let id = $(this).val();
        let state = {
            url: base_url("/branches/getCities"),
            data: {
                id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    let option = "";
                    res.data.map(function (key) {
                        option += `<option value='${key.city_id}'>${key.city_name}</option>`;
                    });
                    $("#b-citie").html(`<option value='' >Select city</option>
                    ${option}
               </select>`);
                }
            },
        };
        $.ajax(state);
    });
});


