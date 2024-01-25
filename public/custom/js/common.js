$(function () {
    "use strict";


    // To make Pace works on Ajax calls
    $(document).ajaxStart(function () {
        Pace.restart()
    })
    $('.ajax').click(function () {
        $.ajax({
            url: '#', success: function (result) {
                $('.ajax-content').html('<hr>Ajax Request Completed !')
            }, error: function (result) {
                alert("Hello! I am an alert box!");
            },
        })
    })

    $('#slct-switch-role').change(function () {
        window.location = BASE_URL + `/auth/loginAs/${$(this).val()}/${$(this).find('option[selected]').attr('cid')}`;
    })
})