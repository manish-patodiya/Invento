<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class='mdi mdi-security'></i> Permissions</h4>
                </div>
                <a href="<?php echo base_url("settings/roles") ?>" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>
            </div>

        </div>

        <!-- Main content -->
        <section class="content">
            <div class='card col-md-12 p-4'>
                <form id='frm-edit-permissions'>
                    <input type="hidden" name='role_id' value='<?=$role_id?>' />
                    <div class='text-end'>
                        <input type="checkbox" id='chk-all' />
                        <label for="chk-all">Select all permissions</label>
                    </div>
                    <div class="card-body">
                        <?php print_permission_menu($permissions, $modules, $mid = 0)?>
                        <button class='btn btn-sm btn-success pull-right mb-3'>Update</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>

<script>
$(function() {
    let chk = 0;
    $('.permission').map(function(e) {
        if (!$(this).is(':checked')) {
            chk++;
        }
    })
    if (chk == 0) {
        $('#chk-all').attr('checked', true);
    }

    $('#frm-edit-permissions').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: base_url('/settings/roles/update_permissions'),
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    showToast(res.msg, '', 'success', 'top-right');
                    setTimeout(() => {
                        window.location = base_url('/settings/roles');
                    }, 1000);
                }
            }
        });
    })
    $(document).on('click', '.permission', function() {
        let chk_box_id = $(this).attr('id');
        if ($(this).is(":checked")) {
            $('#module-' + chk_box_id).attr('checked', true);
        } else {
            $('#module-' + chk_box_id).attr('checked', false);
        }
    })
    $('#chk-all').click(function(e) {
        if ($(this).is(':checked')) {
            $('.module,.permission').attr('checked', true);
        } else {
            $('.module,.permission').attr('checked', false);
        }
    })
})

function showToast(heading, text, type, direction = 'top-left') {
    $.toast({
        heading: heading,
        text: text,
        position: direction,
        loaderBg: '#ff6849',
        icon: type,
        hideAfter: 5000,
        stack: 6,
    });
}
</script>