<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.mx-5 {
    margin-left: 8px !important;
    margin-right: 21px !important;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; Branch List</h4>
                </div>
                <?php if (check_method_access('branches', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/branches/add_branches');?>" class="btn btn-info btn-sm"><i
                            class="fa fa-plus"></i>
                        Create Branch</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <!-- For Messages -->


            <div class="alert alert-success" style="display:none" id="success-msg"></div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="na_datatable" class="table" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%" scope="col">SN</th>
                                    <th scope="col">Branch</th>
                                    <th width='25%' scope="col">User</th>
                                    <th width='17%' scope="col">Created On</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/branches.js')?>"></script>