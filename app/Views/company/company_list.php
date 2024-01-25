<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.col-md-1 {
    flex: 0 0 auto;
    width: 11.333333%;
}
</style>
<div class="alert alert-success" style="display:none" id="success-msg"></div>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; Company List</h4>
                </div>
                <?php if (check_method_access('companies', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/companies/add_company');?>" class="btn btn-sm btn-info"><i
                            class="fa fa-plus"></i>
                        Create Company</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <!-- For Messages -->
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="na_datatable" class="table table-hover no-wrap" width="100%">
                        <thead>
                            <tr>
                                <th width='10%' scope="col">Logo</th>
                                <th width='20%' scope="col">Company</th>
                                <th width='20%' scope="col">User</th>
                                <th width='20%' scope="col">License Expiry Date</th>
                                <th width='20%' scope="col">Branch Detail</th>
                                <th width="100" class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
// echo view('sub_modals/viewbranch/branch_list.php');
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/company.js')?>"></script>