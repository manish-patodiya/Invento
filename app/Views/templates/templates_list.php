<?php
echo view('include/header/header_top');
echo view('include/header/header');
echo view('include/sidebar/sidebar_member');
?>
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title"><i class="fa fa-list"></i> Create Templates </h4>
                </div>
                <?php if (check_method_access('templates', 'add', true)): ?>
                <div class="d-inline-block float-right">
                    <a href="<?=base_url('/templates/add_templates');?>" class="btn btn-info btn-sm"><i
                            class="fa fa-plus"></i>
                        Create Template</a>
                </div>
                <?php endif;?>
            </div>
        </div>
        <section class="content">
            <div class="alert alert-success" style="display:none" id="success-msg"></div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="na_datatable" class="table" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">Title </th>
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
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/templates.js')?>"></script>