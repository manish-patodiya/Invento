function base_url(uri) {
    return BASE_URL + uri;
}

$(function () {
    "use strict";

    // updata for branch general setting are login with branch manager .................
    $("#bran_updeted_detail").submit(function (e) {
        e.preventDefault();
        let id = $("#branches_id").val();
        let form_data = $("#bran_updeted_detail").serializeArray();
        console.log(form_data)
        var formData = new FormData(this);
        let invoice = {
            url: base_url("/settings/generalsetting/branch_updata_details/" + id),
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
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
        $.ajax(invoice)
    })


    // updata for branch transaction setting are login to branch manager...............
    $("#branch_trans_details").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let trans_details = {
            url: base_url("/settings/transactionsetting/branch_tranch_add"),
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
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
        $.ajax(trans_details);
    })


    // updata for branch invoice concept details are login to branch manager...............

    $("#branch_invoice_concept_details").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let invoice = {
            url: base_url("/settings/invoiceconcept/add_branch_invoice_concept"),
            method: "post",
            data: formData,
            contentType: false,
            processData: false,
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