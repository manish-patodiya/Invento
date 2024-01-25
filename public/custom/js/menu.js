function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    "use strict";


    $('#add_menu').click(function (e) {
        e.preventDefault();
        if ($('#input_box').hasClass('d-none')) {
            $('#input_box').removeClass('d-none');
        } else {
            let label = {
                url: base_url("/settings/menu/add"),
                data: { menu: $('#menu').val() },
                method: "post",
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
                        setTimeout(() => {
                            window.location = base_url("/settings/menu");
                        }, 3000);
                        $('#input_box').addClass('d-none');
                    }
                }
            }
            $.ajax(label)
        }
    })


    $('#media-group').change(function () {
        let module_id = $('#media-group').val();
    })
    $('#add_module').click(function () {
        let module_id = $('#media-group').val();
        let menu_name = $('#get_val_menu').val();
        let info = {};
        info.menu_name = menu_name;
        info.module_id = module_id;

        let list = {
            url: base_url("/settings/menu/add_module_id"),
            data: info,
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
        $.ajax(list)
    })


    $('#get_val_menu').change(function () {
        let menu_name = $('#get_val_menu').val();
        get_all_module();
    })

    function get_all_module() {
        let id = $('#get_val_menu').val();
        let list = {
            url: base_url("/settings/menu/get_module"),
            data: {
                id: id
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                let selected_module = [];
                let menu_id = '';
                if (res.module_name) {
                    selected_module = res.module_name.module_id.split(',');
                    menu_id = res.module_name.id;
                }
                $('#menu_id').val('');
                $('#media-group').html('');

                $('#menu_id').val(menu_id);
                res.module.map(function (list) {
                    let slct = $.inArray(`${list.id}`, selected_module) != -1 ? 'selected' : '';
                    console.log(slct);
                    $('#media-group').append(
                        `<option value="${list.id}" ${slct}>${list.title}</option>`
                    )
                })


                $('.duallistbox').bootstrapDualListbox('refresh', true)
            }
        }
        $.ajax(list)

    }
    get_all_module();
    $('.duallistbox').bootstrapDualListbox()



    $('#frm-add-modules').submit(function (e) {
        e.preventDefault();
        let label = {
            url: base_url("/settings/menu/edit"),
            data: $(this).serialize(),
            method: "post",
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
                    get_all_module();
                }
            }
        }
        $.ajax(label)
    })


})