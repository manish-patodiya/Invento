<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<link rel="stylesheet" href="<?=base_url('public/assets/vendor_components/jquery-ui/jquery-ui.css')?>">
<link rel="stylesheet"
    href="<?=base_url('public/assets/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css')?>">

<style>
li {
    list-style: none;
}

.ui-state-highlight {
    height: 1.5em;
    line-height: 1.2em;
}

.tooltip-inner {
    background-color: #ccc !important;
    box-shadow: 0px 0px 4px black;
    opacity: 1 !important;
}

.editable-click,
a.editable-click,
a.editable-click:hover {
    border-bottom: 0;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-edit"></i> Update Header Template</h4>
                </div>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/templates');?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i>
                        Template List</a>
                </div>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="m-3">
                            <div class='row'>
                                <div class='form-group col-md-3'>
                                    <label for="">Place Logo:</label>
                                    <div>
                                        <input name='position' type="radio" id="radio-left" value="1" checked>
                                        <label for="radio-left">Left</label>
                                        <input name='position' type="radio" id="radio-center" value="2">
                                        <label for="radio-center">Center</label>
                                        <input name='position' type="radio" id="radio-right" value="3"
                                            class="radio-col-success">
                                        <label for="radio-right">Right</label>
                                    </div>
                                </div>
                                <input type="hidden" id='temp_id' value='<?=$temp['id']?>' />
                                <div class='form-group col-md-9'>
                                    <label for="">show/hide:</label>
                                    <div>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">Address</label>
                                            <input type="checkbox" id="checkbox-adrs" value="adrs" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">Mobile</label>
                                            <input type="checkbox" id="checkbox-mob" value="mob" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">Email</label>
                                            <input type="checkbox" id="checkbox-email" value="email" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">Website URL</label>
                                            <input type="checkbox" id="checkbox-url" value="url" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">GST</label>
                                            <input type="checkbox" id="checkbox-gst" value="gst" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">CIN</label>
                                            <input type="checkbox" id="checkbox-cin" value="cin" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                        <label class="switch switch-border switch-primary me-4">
                                            <label for="">IEC</label>
                                            <input type="checkbox" id="checkbox-iec" value="iec" checked>
                                            <span class="switch-indicator"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="main-div" class='border border-dark'>
                                <?=$temp['content']?>
                            </div>
                            <button class='btn btn-sm btn-success pull-right mt-2'
                                onclick='save_temp();'>Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div id='popover-img'>
    <input type="file" class='inpt-set-file' />
</div>
<div id='popover_template'>
    <a class='btn btn-primary btn-sm btn-edit-tmp tt' title='Edit text' val='1'><i class='fa fa-edit'></i></a>
    <a class='btn btn-success btn-sm btn-edit-tmp tt' title='Increase size' val='2'><i class='fa fa-plus'></i></a>
    <a class='btn btn-danger btn-sm btn-edit-tmp tt' title='Reduce size' val='3'><i class='fa fa-minus'></i></a>
</div>


<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/assets/vendor_components/jquery-ui/jquery-ui.js')?>">
</script>
<script
    src="<?=base_url('public/assets/vendor_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js')?>">
</script>

<script>
function save_temp() {
    id = $('#temp_id').val();
    content = $('#main-div').html();
    $.ajax({
        type: "post",
        url: base_url("/templates/edit/" + id),
        data: {
            'temp_id': id,
            'content': content,
            'submit': true,
        },
        dataType: "json",
        success: function(res) {
            if (res.status == 1) {
                window.location = base_url("/templates");
            }
        }
    });
}
$(function() {

    $.fn.editable.defaults.mode = 'inline';
    $(document).on('click', '.btn-edit-tmp', function(e) {
        e.stopPropagation();
        $('.tt').tooltip('hide');
        let val = $(this).attr('val');
        if (val == 1) {
            $('.editable').popover('hide');
            $(pop_ele).editable('toggle');
        } else if (val == 2) {
            let size = $(pop_ele).attr('size');
            $(pop_ele).attr('size', ++size <= 6 ? size : 6);
        } else if (val == 3) {
            let size = $(pop_ele).attr('size');
            $(pop_ele).attr('size', --size >= 1 ? size : 1);
        }
    })

    $('.editable').popover({
        trigger: 'manual',
        placement: 'top',
        sanitize: false,
        customClass: "",
        html: true,
        content: function(e) {
            var $buttons;
            if ($(this)[0]['localName'] == 'img') {
                $buttons = $('#popover-img').html();
            } else {
                $buttons = $('#popover_template').html();
            }
            return $buttons;
        }
    }).on("mouseenter", function() {
        var _this = this;
        $(this).popover("show");
        pop_ele = _this;
        $(".popover").on("mouseleave", function() {
            $(_this).popover('hide');
            $('.tt').tooltip('hide');
        });
    }).on("mouseleave", function() {
        var _this = this;
        setTimeout(function() {
            if (!$(".popover:hover").length) {
                $('.tt').tooltip('hide');
                $(_this).popover("hide");
            }
        }, 100);
    }).on('shown.bs.popover', function() {
        $('.tt').tooltip();
    });

    $("#sortable").sortable({
        placeholder: "ui-state-highlight"
    });

    $('input[type="radio"]').change(function() {
        val = $(this).val();
        if (val == 1) {
            $("#text-portion").before($("#img-portion"));
            $("#img-portion").attr('position', '1');
            $("#img-portion").removeClass('d-none');
            $("#logo").addClass('d-none');
        } else if (val == 2) {
            $("#img-portion").attr('position', '2');
            $("#img-portion").addClass('d-none');
            $("#logo").removeClass('d-none');
        } else {
            $("#text-portion").after($("#img-portion"));
            $("#img-portion").attr('postion', '3');
            $("#img-portion").removeClass('d-none');
            $("#logo").addClass('d-none');
        }
    })

    $('input[type="checkbox"]').change(function() {
        val = $(this).val();
        is_chkd = $(this).is(":checked");

        if (is_chkd) {
            $("#" + val).removeClass('d-none');
        } else {
            $("#" + val).addClass('d-none');
        }
    })

    $('input[type="checkbox"]').map(function() {
        val = $(this).val();
        if ($('#' + val).hasClass('d-none')) {
            $(this).attr('checked', false);
        }
    })
    if ($("#img-portion").attr('position') == 2) {
        $('#radio-center').prop('checked', true);
    } else if ($("#img-portion").attr('position') == 3) {
        $('#radio-right').prop('checked', true);
    }

    $(document).on('change', 'input[type=file]', function() {
        var file_data = $(this).prop('files')[0];
        // if (file_data) {
        //     $("table img").attr("src", URL.createObjectURL(file_data));
        // };
        var myFormData = new FormData();
        myFormData.append('file', file_data);
        console.log(myFormData);

        $.ajax({
            type: "post",
            url: base_url("/templates/upload_logo"),
            data: myFormData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {

                    no_img = base_url("/public/images/avatar/avatar-1.png");
                    user_img = res.info ? res.info : no_img;

                    $("#main-div table img").attr("src", user_img);
                }

            }
        });

    });
});
</script>