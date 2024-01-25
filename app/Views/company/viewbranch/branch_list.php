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
<input type="hidden" value="<?=$cid?>" name="cid" id="cid">
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i>&nbsp; Branch List</h4>
                </div>

                <?php if (check_method_access('companies', 'view', true)): ?>
                <div class="d-inline-block float-right m-1">
                    <a href="<?=base_url('/companies');?>" class="btn btn-sm btn-info"><i class="fa fa-list"></i>
                        Company List</a>
                </div>
                <?php endif;?>

                <?php if (check_method_access('companies', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url("/companies/ADDVIEWBRANCH/$cid");?>" class="btn btn-sm btn-info"><i
                            class="fa fa-plus"></i>
                        Create Branch</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="view-Branch-details" class="table" width="100%">
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


<script src="<?=base_url('public/custom/js/company.js')?>"></script>