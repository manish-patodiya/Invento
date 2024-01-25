function base_url(uri) {
    return BASE_URL + uri;
}

$(function () {
    $("#na_datatable").DataTable({
        ajax: {
            url: base_url("/companies/companysList"),
            dataSrc: "details",
        },
        columnDefs: [{
            targets: [5],
            orderable: false,
            width: "13%",
            class: "text-end",
        },
        {
            targets: [0, 3, 4],
            class: "text-center",
            orderable: false,
        },
        {
            targets: [1],
            orderable: false,
        },
        ],
    });

    $("#user_img").change(function () {
        var file = this.files[0];
        let path = console.log(file);
        if (file) {
            $("#logo").attr("src", URL.createObjectURL(file));
        }
    });
    $("#cho_img").click(function () {
        $("#user_img").click();
    });

    $(document).on("click", "#user", function () {
        $("#company").addClass("d-none");
        $("#userdetail").addClass("d-block");
        $("#userdetail").removeClass("d-none");
    });
    $(document).on("click", "#back", function () {
        $("#userdetail").addClass("d-none");
        $("#company").addClass("d-block");
        $("#company").removeClass("d-none");
    });

    var btn_save
    $("#company_detail").submit(function (e) {
        e.preventDefault();
        $pass = $('input[name="password"]').val().length;
        if ($pass >= 8) {
            var formData = new FormData(this);
            let company = {
                url: base_url("/companies/add"),
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
                        setTimeout(function () {
                            window.location = base_url("/companies");
                        }, 3000);
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
            $.ajax(company);
        }
    });

    $("#edit_company_detail").submit(function (e) {
        e.preventDefault();
        let id = $("#company_id").val();
        let form_data = $("#edit_company_detail").serializeArray();
        var formData = new FormData(this);
        let company = {
            url: base_url("/companies/edit/" + id),
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
                    setTimeout(function () {
                        window.location = base_url("/companies");
                    }, 3000);
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
        $.ajax(company);
    });

    $(document).on("click", ".sup_delete", function () {
        let id = $(this).attr("company_id");
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
                        url: base_url("/companies/delete_company"),
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

    $(document).on('click', '.viewbranch', function () {
        cid = $(this).attr('cid');
        window.location = base_url("/companies/viewBranch/" + cid);
    })



    // Inside the list, click on the button View branch picture to display the list of branches which appear in the company's behavior
    //We get to show the list of branch per company id basis
    //add, Edit, Delete, functionality form company id basis
    $("#view-Branch-details").DataTable({
        ajax: {
            url: base_url("/companies/branches_List"),
            dataSrc: "details",
            data: function name(p) {
                p.cid = $("#cid").val();
            },
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


    var btn_save;
    $("#add-view-branches-details").submit(function (e) {
        e.preventDefault();
        $pass = $('input[name="password"]').val().length;
        cid = $('#cid').val();
        if ($pass >= 8) {
            var formData = new FormData(this);
            let branches = {
                url: base_url("/companies/add_view_branch"),
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
                            window.location = base_url("/companies/viewBranch/" + cid);
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

    $("#view-bran-updeted-detail").submit(function (e) {
        e.preventDefault();
        let id = $("#branches_id").val();
        cid = $('#cid').val();
        var formData = new FormData(this);
        let branches = {
            url: base_url("/companies/edit_view_branch/" + id),
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
                        window.location = base_url("/companies/viewBranch/" + cid);
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
                        url: base_url("/companies/deleted_branches"),
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
                                $("#view-Branch-details").DataTable().ajax.reload();
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

function error(_this) {
    url = base_url("/public/uploads/image_found/no-image.jpg");
    $(_this).attr("src", url);
}
function getPincode() {
    pinval = $('#pincode').val();
    $.ajax({
        url: base_url("/companies/getPincode"),
        data: {
            pincode: pinval,
        },
        method: "post",
        dataType: "json",
        success: function (res) {
            if (res.status == 1) {

                let city_id = res.city_id;

                $("#state").val(res.stateinfo.state_id);

                let option = "";
                res.cityinfo.map(function (key) {
                    option += `<option value='${key.city_id}' ${key.city_id == city_id ? 'selected' : ''}>${key.city_name}</option>`;
                });
                $("#citie").html(option);

                $.toast({
                    text: res.msg,
                    position: "top-right",
                    loaderBg: "#ff6849",
                    icon: "success",
                    hideAfter: 3500,
                    stack: 6,
                });

            } else {
                $.toast({
                    text: res.msg,
                    position: "top-right",
                    loaderBg: "#ff6849",
                    icon: "success",
                    hideAfter: 3500,
                    stack: 6,
                });
            }
        }

    })
}