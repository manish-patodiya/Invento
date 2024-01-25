function base_url(uri) {
    return BASE_URL + uri;
}
$(function() {
    "use strict";


    $("#user_profile_updete_detail").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/profile/profile/update_User_Profile"),
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
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 6
                    });
                    setTimeout(function() {
                        window.location = base_url("/profile/profile");
                    }, 2000);

                }
            }
        }
        $.ajax(unit);
    })

    $("#user_password").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let unit = {
            url: base_url("/profile/profile/changePass"),
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
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3000,
                        stack: 6
                    });
                    setTimeout(function() {
                        window.location = base_url("/auth/logout");
                    }, 2000);
                } else {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3000,
                        stack: 6
                    });
                }
            }
        }
        $.ajax(unit);
    });

    $("#user_img").change(function() {
        var file = this.files[0];
        let path =
            console.log(file);
        if (file) {
            $("#logo").attr('src',
                URL.createObjectURL(file)
            );
        }
    })

    $('#cho_img').click(function() {
        $('#user_img').click();
    })

    $('#area-image').hover(function() {
        $('#cho_img').removeClass('d-none');
    }, function() {
        $('#cho_img').addClass('d-none');
    })

})