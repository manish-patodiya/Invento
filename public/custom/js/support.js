function base_url(uri) {
    return BASE_URL + uri;
}
$(function () {
    let support_table = () => {
        $("#support_table").DataTable({
            ajax: {
                url: base_url("/support/support_list"),
                dataSrc: "details",
                data: function (data) {
                    data.tid = $("#status-shown-in-tbl").val();
                },
            },
            columnDefs: [
                { width: "5%", targets: 0 },
                { width: "15%", targets: 1 },
                {
                    targets: [3, 4, 5],
                    orderable: false,
                },
                { width: "8%", targets: 3 },
                { width: "15%", targets: 4 },
                { width: "15%", targets: 5, class: "text-end" },
            ],
        });
    };
    support_table();
    $(document).on("click", ".box-inverse", function () {
        let tid = $(this).attr("tid");
        $("#status-shown-in-tbl").val(tid);
        $("#support_table").DataTable().ajax.reload();
    });

    $(document).on("click", ".add", function () {
        $("#mdl_add_suppot").modal("show");
    });

    $(document).on("click", ".btn-chng-sts", function () {
        $(this).hide();
        $(this).parent().children("select").show();
        $(this).parent().children("select").trigger("click");
    });

    $(document).on("change", ".select-statu-id", function () {
        let ticket_id = $(this).attr("sup-ticket-id");
        let sel_status_id = $(this).val();
        let ajax = {
            url: base_url("/support/update_status"),
            data: {
                sel_status_id: sel_status_id,
                ticket_id: ticket_id,
            },
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
                        hideAfter: 1000,
                        stack: 6,
                    });
                    setTimeout(function () {
                        reload_support_tickets();
                    }, 100);
                }
            },
        };
        $.ajax(ajax);
    });

    function reload_support_tickets() {
        $.ajax({
            url: base_url("/support/supp_tickets_count"),
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    let ids = [];
                    let total = 0;
                    res.data.map(function (item) {
                        total += Number(item.count);
                        ids.push(Number(item.status_id));
                        switch (Number(item.status_id)) {
                            case 1:
                                console.log(item.count);
                                $("#panding_tickets").find(".count").html(item.count);
                                break;
                            case 2:
                                console.log(item.count);
                                $("#compl_tickets").find(".count").html(item.count);
                                break;
                            case 3:
                                console.log(item.count);
                                $("#hold_tickets").find(".count").html(item.count);
                                break;
                            case 4:
                                console.log(item.count);
                                $("#reject_tickets").find(".count").html(item.count);
                                break;
                        }
                    });
                    if ($.inArray(1, ids) == -1)
                        $("#panding_tickets").find(".count").html(0);
                    if ($.inArray(2, ids) == -1)
                        $("#compl_tickets").find(".count").html(0);
                    if ($.inArray(3, ids) == -1)
                        $("#hold_tickets").find(".count").html(0);
                    if ($.inArray(4, ids) == -1)
                        $("#reject_tickets").find(".count").html(0);
                    $("#total_tickets").find(".count").html(total);
                }
            },
        });
        $("#support_table").DataTable().ajax.reload();
    }

    var btn_save;
    $("#support_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let support = {
            url: base_url("/support/add"),
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
                    $("#mdl_add_suppot").modal("hide");
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
                        reload_support_tickets();
                    }, 100);
                    $("#descriptions").val("");
                    $("#subject").val("");
                }
            },
            complete: function () {
                $("#btn-save").attr("disabled", false).html(btn_save);
            },
        };
        $.ajax(support);
    });

    $(document).on("click", ".sup_delete", function () {
        let ticket_id = $(this).attr("support_id");
        console.log(ticket_id);
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this state",
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
                        url: base_url("/support/deleted"),
                        method: "post",
                        data: {
                            ticket_id: ticket_id,
                        },
                        dataType: "json",
                        success: function (res) {
                            if (res.status == 1) {
                                console.log(res);
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
                                    reload_support_tickets();
                                }, 100);
                            } else {
                                swal("Deletion Failed!", res.msg, "error");
                            }
                        },
                    });
                }
            }
        );
    });

    $("#edit_support_detail").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let states = {
            url: base_url("/support/edit"),
            data: formData,
            contentType: false,
            processData: false,
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#mdl_edit_suppot").modal("hide");
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
                        reload_support_tickets();
                    }, 100);
                }
            },
        };
        $.ajax(states);
    });
    $(document).on("click", ".sup_update", function () {
        let id = $(this).attr("support_id");
        console.log(id);
        let states = {
            url: base_url("/support/get_support_id"),
            data: {
                ticket_id: id,
            },
            method: "post",
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $("#supprot_id").val(res.data.ticket_id);
                    $("#e_subject").val(res.data.subject);
                    $("#e_descriptions").val(res.data.description);
                    $("#mdl_edit_suppot").modal("show");
                }
            },
        };
        $.ajax(states);
    });

    $(document).on("click", ".description", function () {
        let id = $(this).attr("support_id");
        $("#support_tickets_view").modal("show");
        let support = {
            url: base_url("/support/get_all_support"),
            method: "post",
            data: {
                ticket_id: id,
            },
            dataType: "json",
            success: function (res) {
                if (res.status == 1) {
                    $(".subject").html(res.name.subject);
                    $("#description").html(res.name.description);
                    $("#date").html(res.name.newdate);
                    $("#branch_name").html(res.name.branch_name);
                    $("#branch_email").html(res.name.branch_email);
                    let status = res.name.status_id;
                    if (status == 1) {
                        $("#status").html(
                            `<label class='badge badge-danger'> Pending</lable>`
                        );
                    } else if (status == 2) {
                        $("#status").html(
                            `<label class='badge badge-success'> Complete</lable>`
                        );
                    } else if (status == 3) {
                        $("#status").html(
                            `<label class='badge badge-warning'> On Hold</lable>`
                        );
                    } else if (status == 4) {
                        $("#status").html(
                            `<label class='badge badge-danger bg-orange'> Retecte</lable>`
                        );
                    }
                }
            },
        };
        $.ajax(support);
    });
});