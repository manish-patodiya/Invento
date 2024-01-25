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
                    <h3 class="page-title">Profile</h3>
                </div>
                <a href="#" onclick="history.back();" class="pull-right me-2">
                    <i class="fa fa-long-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-body mx-4">
                    <form class="col-12" id="user_profile_updete_detail" onsubmit="return false">
                        <input type="hidden" value='<?=$info->id?>' name="user_id">
                        <div class='d-flex align-itmes-center mb-4'>
                            <div class='position-relative border border-dark rounded-circle' id='area-image'>
                                <div class='' style=' height: 80px; width:80px;'>
                                    <img src="<?=$info->user_img == "" ? base_url('public/images/avatar/avatar-1.png') : $info->user_img?>"
                                        id="logo" class="logo rounded-circle" style='height:100%; width:100%;' />
                                </div>
                                <div class='position-absolute top-0 start-0 end-0 bg-light rounded-circle d-flex d-none align-items-center justify-content-center'
                                    style='height:100%;width:100%;opacity:0.95;' id="cho_img">
                                    <i class='mdi mdi-camera fa-2x'></i>
                                </div>
                                <input type="file" class='d-none' name="logo" id="user_img">
                            </div>
                            <div class='ms-4'>
                                <h3><?=$info->first_name . " " . $info->last_name?> (<?=$admin_roles->role?>)</h3>
                                <p>
                                    <?=$info->email?>
                                </p>
                            </div>
                        </div>
                        <div class='mt-2'>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>First Name</label>
                                    <div class="controls">
                                        <input type="text" name="firat_name" class="form-control" id="inputName"
                                            placeholder="" value="<?=$info->first_name?>"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Last Name</label>
                                    <div class="controls">
                                        <input type="text" class="form-control" name="last_name" id="inputName"
                                            placeholder="" value="<?=$info->last_name?>"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <div class="controls">
                                        <input type="email" class="form-control" name="email" id="inputEmail"
                                            placeholder="" value="<?=$info->email?>"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phone</label>
                                    <div class="controls">
                                        <input type="number" class="form-control" name="phone" id="inputPhone"
                                            placeholder="" value="<?=$info->phone?>"
                                            data-validation-required-message="This field is required">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class='card'>
                <div class="card-header flex-column align-items-start">
                    <h4>Change Password</h4>
                    <p class='m-0'>This password is used for authentication.</p>
                </div>
                <div class="card-body mx-4">
                    <form id="user_password" onsubmit="return false">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <div class="controls">
                                    <label>Old Password</label>
                                    <input type="text" class="form-control" name="old_password" id="old_password"
                                        placeholder=" " data-validation-required-message="This field is required">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <div class="controls">
                                    <label>New Password</label>
                                    <input type="text" class="form-control" name="new_password" id="new_password"
                                        placeholder="" data-validation-required-message="This field is required">
                                </div>
                            </div>
                        </div>
                        <div class=" row">
                            <div class="form-group col-md-4">
                                <div class="controls">
                                    <label>Confirm Password</label>
                                    <input type="text" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder=""
                                        data-validation-required-message="This field is required">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">Update</button>
                    </form>
                </div>
            </div>
    </div>
    <!-- <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <div class="box box-widget widget-user">
                        <form class="form-horizontal form-element col-12" id="user_profile_updete_detail"
                            onsubmit="return false">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class=" d-flex flex-column align-items-center justify-content-center">
                                        <div class='d-flex bg-secondary  align-items-center justify-content-center mb-2'
                                            style='height: 200px; width:200px;'>
                                            <img src="<?=$info->user_img == "" ? base_url('public/images/avatar/avatar-1.png') : $info->user_img?>"
                                                id="logo" class="logo " style='max-height:100%; max-width:100%;'>
                                        </div>

                                        <a type="text" class="btn btn-info btn-sm" id="cho_img">Choose Image</a>
                                        <input type="file" class='d-none' name="logo" id="user_img"
                                            accept=".png, .jpg, .jpeg, .gif, .svg">
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p>Name</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p>:<span
                                                        class="text-gray ps-10"><?=$info->first_name . " " . $info->last_name?></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p>Admin Role</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p>:<span class="text-gray ps-10"><?=$admin_roles->role?></span></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p>Email</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p>:<span class="text-gray ps-10"><?=$info->email?></span> </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p>Phone No</p>
                                            </div>
                                            <div class="col-md-9">
                                                <p>:<span class="text-gray ps-10"><?=$info->phone?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <div class="col-md-8 col-lg-8 col-xl-8">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a class="active" href="#profile" data-bs-toggle="tab">Profile</a></li>
                            <li><a href="#ch_pass" data-bs-toggle="tab">Change Password</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="active tab-pane" id="profile">
                                <div class="box no-shadow">
                                    <input type="hidden" value='<?=$info->id?>' name="user_id">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-md-2 form-label">First Name</label>

                                        <div class="controls col-md-7">
                                            <input type="text" name="firat_name" class="form-control" id="inputName"
                                                placeholder="" value="<?=$info->first_name?>"
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-md-2 form-label">Last Name</label>

                                        <div class="controls col-md-7">
                                            <input type="text" class="form-control" name="last_name" id="inputName"
                                                placeholder="" value="<?=$info->last_name?>"
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-md-2 form-label">Email</label>

                                        <div class=" controls col-md-7">
                                            <input type="email" class="form-control" name="email" id="inputEmail"
                                                placeholder="" value="<?=$info->email?>"
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPhone" class="col-md-2 form-label">Phone</label>

                                        <div class="controls col-md-7">
                                            <input type="number" class="form-control" name="phone" id="inputPhone"
                                                placeholder="" value="<?=$info->phone?>"
                                                data-validation-required-message="This field is required">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="ms-auto col-sm-10">
                                            <button type="submit" class="btn btn-info btn-sm">Save</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane" id="ch_pass">
                                <div class="box no-shadow">
                                    <form class="form-horizontal col-md-12" id="user_password" onsubmit="return false">
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="inputName" class="form-label">Old Password</label>
                                            </div>
                                            <div class="controls col-md-7">
                                                <input type="text" class="form-control" name="old_password"
                                                    id="old_password" placeholder=" "
                                                    data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="inputName" class="form-label">New Password</label>
                                            </div>
                                            <div class="controls col-md-7">
                                                <input type="text" class="form-control" name="new_password"
                                                    id="new_password" placeholder=""
                                                    data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="inputEmail" class="form-label">Confirm Password</label>
                                            </div>

                                            <div class="controls col-md-7">
                                                <input type="text" class="form-control" name="confirm_password"
                                                    id="confirm_password" placeholder=""
                                                    data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="ms-auto col-sm-9">
                                                <button type="submit" class="btn btn-info btn-sm">Change
                                                    Passowrd</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
    <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
</div>
<?php
echo view('include/footer/footer.php');
?>

<script src="<?=base_url('public/custom/js/user_profile.js')?>"></script>