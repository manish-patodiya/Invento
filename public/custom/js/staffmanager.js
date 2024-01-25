function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    $("#staff-details").DataTable({
        ajax: {
            url: base_url("/staffmanager/stafflist"),
            dataSrc: "details",
            method: "post",
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
    $("#staff_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let staffmanager = {
            url: base_url("/staffmanager/add"),
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
                    window.location = base_url("/staffmanager");
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(staffmanager);
    });

    $("#edit-staff-detail").submit(function (e) {
        e.preventDefault();
        let id = $("#staff-id").val();
        let form_data = $("#edit-staff-detail").serializeArray();
        var formData = new FormData(this);
        let staffmanager = {
            url: base_url("/staffmanager/edit/" + id),
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
                    window.location = base_url("/staffmanager");
                }
            },
        };
        $.ajax(staffmanager);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("uid");
        console.log(id);
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this company",
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
                        url: base_url("/staffmanager/delete"),
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

    $(document).on("change", "#staff-state", function () {
        let id = $(this).val();
        let state = {
            url: base_url("/staffmanager/getCities"),
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
                    $("#staff-citie").html(`<option value='' >Select city</option>
                    ${option}
               </select>`);
                }
            },
        };
        $.ajax(state);
    });
});