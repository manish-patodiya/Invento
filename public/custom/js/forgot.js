function base_url(uri) {
    console.log(uri);
    return BASE_URL + uri;
}
$(function () {
    "use strict";


    // To make Pace works on Ajax calls
    $(document).ajaxStart(function () {
        Pace.restart()
    })
    $("#forgot_password").submit(function (e) {
        e.preventDefault()
        let emailExist = {
            url: base_url("/auth/forget_password"),
            beforeSend: function () {
                $("#btn-forgot").attr("disabled", true);
            },
            method: "post",
            dataType: "json",
            data: $("#forgot_password").serialize(),
            success: function (res) {
                if (res.status == 1) {
                    $('#msg').html(res.msg);
                    console.log(res);
                }
                $("#btn-forgot").attr("disabled", true);
            },
        }
        $.ajax(emailExist);
    })


    $("#reset_password").submit(function (e) {
        e.preventDefault()
        let changePass = {
            url: base_url("/auth/changePass"),
            beforeSend: function () {
                $("#change_password").attr("disabled", true);
            },
            method: "post",
            dataType: "json",
            data: $("#reset_password").serialize(),
            success: function (res) {
                if (res.status == 1) {
                    console.log(res);
                    window.location = base_url();
                }
                $("#change_password").attr("disabled", true);
            },
        }
        $.ajax(changePass);
    })

})
