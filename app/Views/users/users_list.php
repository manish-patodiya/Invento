<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.select-code {
    background-color: #fff;
}

.modal-footer {
    border-top-color: #f3f6f9;
    display: flex;
    border-top: 0px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Uers List </h4>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="box">

                        <input type="hidden" id='status-shown-in-tbl' value=''>
                        <div class="box-body p-15">
                            <div class="col-12 table-responsive">
                                <table id="user_details" class="table mt-0 table-hover no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="col">SN</th>
                                            <th class="col">Photo</th>
                                            <th class="col">User</th>
                                            <th class="col">Role</th>
                                            <th class="col">Status</th>
                                            <th class="col">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('users/edit_user_model.php');
echo view('users/deactiveModal.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/user.js')?>"></script>
<script>

</script>