function base_url(uri) {
    return BASE_URL + uri;
}

$(function () {
    "use strict";

    // update to general settng  for company admin and login with company admin ................
    $("#edit_general_settings").submit(function (e) {
        e.preventDefault();
        let id = $("#company_id").val();
        let form_data = $("#edit_general_settings").serializeArray();
        var formData = new FormData(this);
        let company = {
            url: base_url("/settings/generalsetting/comp_update_details/" + id),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $.toast({
                        text: res.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            }
        }
        $.ajax(company);
    })



    // uploda to logo for comapny general setting ...................

    $("#user_img").change(function () {
        var file = this.files[0];
        let path =
            console.log(file);
        if (file) {
            $("#logo").attr('src',
                URL.createObjectURL(file)
            );
        }
    })
    $('#cho_img').click(function () {
        $("#user_img").click();
    })




    //updata to start for company admin and login with company admin ................
    $("#edit_start_no").submit(function (e) {
        e.preventDefault();
        let form_data = $("#edit_start_no").serializeArray();
        var formData = new FormData(this);
        let start_no = {
            url: base_url("/settings/transactionsetting/start_no"),
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                    // window.location = base_url("/settings/generalsetting");
                }
            }
        }
        $.ajax(start_no);
    })


    //updata to invoice concept details for company admin and login with company admin ................

    $("#invoice_concept_details").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let invoice = {
            url: base_url("/settings/invoiceconcept/company_invoice_concept"),
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $.toast({
                        // heading: 'Welcome to my Deposito Admin',
                        text: res.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            }
        }
        $.ajax(invoice)
    })

    $(document).on("change", "#g-state", function () {
        let id = $('#g-state').val();
        let state = {
            url: base_url('/settings/generalsetting/getCities'),
            data: {
                'id': id,
            },
            method: 'post',
            dataType: 'json',
            success: function (res) {
                if (res.status == 1) {
                    let option = '';
                    res.data.map(function (key) {
                        option += `<option value='${key.city_id}'>${key.city_name}</option>`
                    });
                    $('#g-citie').html(`<option value='' >Select city</option>
                        ${option}
                   </select>`);
                }
            }
        }
        $.ajax(state);
    })


})
function getPincode() {
    pinval = $('#pincode').val();
    $.ajax({
        url: base_url("/settings/generalsetting/getPincode"),
        data: {
            pincode: pinval,
        },
        method: "post",
        dataType: "json",
        success: function (res) {
            if (res.status == 1) {

                let city_id = res.city_id;

                $("#g-state").val(res.stateinfo.state_id);

                let option = "";
                res.cityinfo.map(function (key) {
                    option +=
                        `<option value='${key.city_id}' ${key.city_id == city_id ? 'selected' : ''}>${key.city_name}</option>`;
                });
                $("#g-citie").html(option);

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