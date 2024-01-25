function base_url(uri) {
    return BASE_URL + uri;
}
$(function() {
    "use strict";

    $("#label").select2({
        tags: true,
    });

    $("#add_label").click(function() {
        let new_label = $("#new_label").val();
        if (new_label != "") {
            let label = {
                url: base_url("/settings/label/add"),
                data: {
                    label: new_label,
                },
                method: "post",
                dataType: "json",
                success: function(res) {
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
                        $("#new_label").val("");
                        labels();
                    }
                },
            };
            $.ajax(label);
        }
    });

    $(document).on("click", "#add_label_value", function() {
        let new_value = $("#new_value").val();
        let cat_id = $(this).attr("cid");
        let label_id = $(this).attr("label_id");
        if (new_value != "") {
            let label = {
                url: base_url("/settings/label/values"),
                data: {
                    cat_id: cat_id,
                    label_id: label_id,
                    value: new_value,
                },
                method: "post",
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        $.toast({
                            heading: "Category new value was inserted successfully",
                            text: res.msg,
                            position: "top-right",
                            loaderBg: "#ff6849",
                            icon: "success",
                            hideAfter: 3500,
                            stack: 6,
                        });
                        $("#new_value").val("");
                        $("#value_box").load(location.href + " #value_box");

                        // $("cat_id").trigger("click")
                    }
                },
            };
            $.ajax(label);
        }
    });
    var EditSuccessAlert = function() {};
    EditSuccessAlert.prototype.init = function() {
        $("#edit_hsn_detail").submit(function(e) {
            e.preventDefault();
            let id = $("#hsn_id").val();
            let form_data = $("#edit_hsn_detail").serializeArray();
            var formData = new FormData(this);
            let hsn = {
                url: base_url("/settings/hsn/edit/" + id),
                data: formData,
                contentType: false,
                processData: false,
                method: "post",
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        window.location.reload();
                        $.toast({
                            // heading: 'Welcome to my Deposito Admin',
                            text: res.msg,
                            position: "top-right",
                            loaderBg: "#ff6849",
                            icon: "success",
                            hideAfter: 3500,
                            stack: 6,
                        });
                    }
                },
            };
            $.ajax(hsn);
        });
    };
    // $.EditSuccessAlert = new EditSuccessAlert, $.EditSuccessAlert.Constructor = EditSuccessAlert $.EditSuccessAlert.init()

    var SweetAlert = function() {};
    SweetAlert.prototype.init = function() {
        //Warning Message
        $(document).on("click", ".sup_delete", function() {
            let id = $(this).attr("hsn_id");
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this data",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Delete",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: base_url("/settings/hsn/delete_hsn"),
                            method: "post",
                            data: {
                                id: id,
                            },
                            dataType: "json",
                            success: function(res) {
                                if (res.status == 1) {
                                    swal("Deleted!", res.msg, "success");
                                    $("#hsn_details_table").DataTable().ajax.reload();
                                } else {
                                    swal("Deletion Failed!", res.msg, "error");
                                }
                            },
                        });
                    }
                }
            );
        });
    };
    //init
    ($.SweetAlert = new SweetAlert()), ($.SweetAlert.Constructor = SweetAlert);

    //initializing
    $.SweetAlert.init();

    $(document).on("click", ".labels", function() {
        let label_id = $(this).attr("lid");
        $.ajax({
            url: base_url("/settings/label/categories/" + label_id),
            method: "post",
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    $(".categories").html("");
                    $(".value_box").html(`<a class="media media-single" href="#">
                    <span class="title">Select category first</span>
                    </a>`);
                    if (res.categories == "") {
                        $(".categories").append(`<a class="media media-single" href="#">
                        <span class="title">No result found</span>
                        </a>`);
                        $(".value_box").html(`<a class="media media-single" href="#">
                        <span class="title">Categories not available</span>
                        </a>`);
                    } else {
                        res.categories.map(function(key) {
                            $(
                                ".categories"
                            ).append(`<a class="media media-single cat_id" label_id="${label_id}" cid="${key.id}" href="#">
<span class="title">${key.category_name}</span>
</a>`);
                            $(".values").html(`<a class="media media-single" href="#">
                            <span class="title">Select category first</span>
                            </a>`);
                        });
                    }
                }
            },
        });
    });
    var values = () => {
        $(document).on("click", ".cat_id", function() {
            let cat_id = $(this).attr("cid");
            let label_id = $(this).attr("label_id");
            console.log(label_id);
            $.ajax({
                url: base_url("/settings/label/values"),
                method: "post",
                data: {
                    cat_id: cat_id,
                    label_id: label_id,
                },
                dataType: "json",
                success: function(res) {
                    if (res.status == 1) {
                        $(".values").html("");
                        if (res.categories == "") {
                            $(".categories").append(`<a class="media media-single" href="#">
                                  <span class="title">No result found</span>
                            </a>`);
                        } else {
                            $(".value_box").html(`
                            <div class="media-list media-list-hover media-list-divided form-group mb-0 m-5">
                                <div class="row">
                                    <div class="input-group ps-3 mb-3">
                                        <input type="text" name="new_value" class="form-control border-0 border-bottom" id="new_value" placeholder="Type to add new value" data-validation-required-message="This field is required">
                                        <button type="button" class="btn btn-sm btn-info rounded-0" id="add_label_value" cid="${cat_id}"
                                        label_id="${label_id}">
                                        Add Value
                                    </button>
                                    </div>
                                </div>
                            </div>
                            <div class="media-list media-list-hover media-list-divided values"></div>`);

                            res.values.map(function(key) {
                                $(
                                    ".values"
                                ).append(`<a class="media media-single val_id" vid="${key.id}" href="#">
                                    ${key.value}
                                </a>`);
                            });
                        }
                    }
                },
            });
        });
    };
    values();
    var labels = () => {
        $.ajax({
            url: base_url("/settings/label/labels"),
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    $(".lid").html("");
                    res.labels.map(function(key) {
                        $(
                            ".lid"
                        ).append(`<a class="media media-single labels" lid="${key.id}" href="#">
            ${key.label}
        </a>`);
                    });
                }
            },
        });
    };
    labels();
});