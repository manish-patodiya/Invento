<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<style>
.btn-lg {
    font-size: 1.286rem;
    padding: 6px 32px;
}
</style>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Staff Manager</h4>
                </div>
                <?php if (check_method_access('staffmanager', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/staffmanager/add_staffmanager');?>" class="btn btn-info btn-sm"><i
                            class="fa fa-plus"></i>
                        Add Staff</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <!-- mein body -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-body table-responsive">
                            <table id="staff-details" class="table" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="1">SN</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone No</th>
                                        <th width="100" class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>
<script src="<?=base_url('public/custom/js/staffmanager.js')?>"></script>