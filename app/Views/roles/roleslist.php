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
                    <h4 class="page-title"><i class='mdi mdi-security'></i> Roles</h4>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class='card'>
                <div class="card-body">
                    <table class='table'>
                        <thead>
                            <th>Role</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $key => $role) {?>
                            <tr>
                                <td><?=$role->role?></td>
                                <td>
                                    <a href="<?=base_url("settings/roles/permissions/$role->id")?>"
                                        style="font-size: 1.2rem;" class='text-primary'><i
                                            class="fa fa-pencil-square-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
</div>
<?php
echo view('include/footer/footer.php');
?>