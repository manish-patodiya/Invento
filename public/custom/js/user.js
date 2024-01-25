function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";

    $("#user_details").DataTable({
        ajax: {
            url: base_url("/users/users_list"),
            dataSrc: "details",
        },
        order: [
            [2, "asc"]
        ],
        columnDefs: [
            { width: "2%", targets: 0, orderable: false },
            { width: "5%", targets: 1, orderable: false },
            { width: "20%", targets: 2, orderable: true },
            { width: "10%", targets: 3, orderable: true, class: "text-center" },
            { width: "10%", targets: 4, orderable: false, class: "text-center" },
            { width: "10%", targets: 5, orderable: false, class: "text-center" },
        ],
    });

    $(document).on("click", ".delete", function () {
        let uid = $(this).attr("uid");
        let cid = $(this).attr("company_id");
        let bid = $(this).attr("branch_id");
        let sid = $(this).attr("staff_id");
        delete_user(uid, cid, bid, sid);
    });

    $(document).on("click", ".update", function () {
        let uid = $(this).attr("uid");
        let cid = $(this).attr("company_id");
        let bid = $(this).attr("branch_id");
        let sid = $(this).attr("staff_id");
        edit_user(uid);
    });

    $(document).on('click', '.deactive', function () {
        let uid = $(this).attr("uid");
        uers_deactive(uid);
    })
    $(document).on('click', '.active', function () {
        let uid = $(this).attr("uid");
        uers_active(uid);
    })

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
});


function update_details_user() {
    $("#frm-edit-user").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let company = {
            url: base_url("/users/update"),
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
                    $("#user_details").DataTable().ajax.reload();
                    $("#mdl-user-model").modal("hide");
                }
            },
        };
        $.ajax(company);
    });
}

function uers_deactive(uid) {
    $.ajax({
        url: base_url("/users/get_user"),
        type: "post",
        dataType: "json",
        data: {
            uid: uid,
        },
        success: function (res) {
            if (res.status == 1) {
                data = res.details;
                $("#user-id").val(data.id);
                $("#mdl-deactive-user-model").modal("show");
            }
        },
    });

}
function edit_user(uid) {
    $.ajax({
        url: base_url("/users/get_user"),
        type: "post",
        dataType: "json",
        data: {
            uid: uid,
        },
        success: function (res) {
            if (res.status == 1) {
                data = res.details;
                $("#uid").val(data.id);
                $("#first_name").val(data.first_name);
                $("#last_name").val(data.last_name);
                $("#email").val(data.email);
                $("#phone").val(data.phone);

                no_img = base_url("/public/images/avatar/avatar-1.png");
                user_img = data.user_img ? data.user_img : no_img;

                $("#logo").attr("src", user_img);
                $("#mdl-user-model").modal("show");
            }
        },
    });
}

function delete_user(uid, cid, bid, sid) {
    swal({
        title: "Are you sure?",
        text: "You want delete user",
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
                    url: base_url("/users/delete_user"),
                    type: "post",
                    data: {
                        user_id: uid,
                        company_id: cid,
                        branch_id: bid,
                        staff_id: sid,
                    },
                    success: function (res) { },
                });
            }
        }
    );
}


var btn_save;
function deactive_user() {
    $("#user-deactive-status-updata").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let company = {
            url: base_url("/users/user_deactive_status_updata"),
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
                    $("#mdl-deactive-user-model").modal("hide");
                    $("#user_details").DataTable().ajax.reload();
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(company);
    });
}

function uers_active(uid) {
    swal({
        title: "Are you sure?",
        text: "You want active user",
        // type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Okay",
        closeOnConfirm: true,
        showLoaderOnConfirm: true,
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: base_url("/users/uers_active"),
                    type: "post",
                    data: {
                        user_id: uid,
                    },
                    success: function (res) {
                        $.toast({
                            // heading: 'Welcome to my Deposito Admin',
                            text: res.msg,
                            position: "top-right",
                            loaderBg: "#ff6849",
                            icon: "success",
                            hideAfter: 3500,
                            stack: 6,
                        });
                        $("#user_details").DataTable().ajax.reload();
                    },
                });
            }
        }
    );
}
function error(_this) {
    url = base_url("/public/uploads/image_found/no-image.jpg");
    $(_this).attr("src", url);
}