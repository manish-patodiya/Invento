<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?=base_url('public/css/style.css')?>">

    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?=base_url('public/css/vendors_css.css')?>">

    <!-- Style-->
    <link rel="stylesheet" href="<?=base_url('public/css/style.css')?>">
    <link rel="stylesheet" href="<?=base_url('public/css/skin_color.css')?>">
    <script>
    const BASE_URL = "<?php echo base_url(); ?>";
    </script>
</head>

<body class="hold-transition theme-primary bg-img"
    style="background-image: url(<?=base_url('public/images/auth-bg/bg-5.jpg');?>)">
    <section style="display: none;" id="sec-login">
        <div class="container h-100">
            <div class=" row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 my-5">
                    <div class="card bg-light text-dark shadow" style="border-radius: 1rem;">
                        <div class="card-body py-5 " style="padding-left:6rem; padding-right:6rem">
                            <div class=" pb-3 text-center">
                                <h2 class="fw-bold">Login as</h2>
                            </div>
                            <?php foreach ($session['user_roles'] as $role) {?>
                            <div class="mb-2 d-grid gap-2">
                                <a class="btn btn-lg btn-primary text-center"
                                    href='<?=base_url("auth/loginAs/$role->role_id/$role->company_id/$role->branch_id")?>'>
                                    <?=ucfirst($role->role) . " of " . $role->company_name . ""?>
                                </a>
                            </div>
                            <?php }?>
                        </div>
                        <div class="text-end mb-3 me-3">
                            <a href="<?=base_url('auth/logout')?>" class="btn btn-sm" style="font-size:12px">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center" style='height:90vh'>
            <div class="col-xl-5 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Login as</h4>
                    </div>
                    <div class="box-body p-0">
                        <div class="media-list media-list-hover">
                            <?php foreach ($session['user_roles'] as $role) {?>
                            <div class="media">
                                <a class="align-self-center"
                                    href='<?=base_url("auth/loginAs/$role->role_id/$role->company_id/$role->branch_id")?>'>
                                    <img class="avatar avatar-lg bg-success-light rounded"
                                        src="<?=$role->company_logo?>" alt="...">
                                    <div class="media-body fw-500 pull-right px-3">
                                        <h4><strong><?=$role->company_name?></strong></h4>
                                        <h5 class="text-fade"><?=ucfirst($role->role)?></h5>
                                    </div>
                                </a>
                            </div>
                            <?php }?>

                        </div>
                    </div>
                    <div class="text-end mb-3 me-3">
                        <a href="<?=base_url('auth/logout')?>" class="" style="font-size:12px">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor JS -->
    <script src="<?=base_url('public/js/vendors.min.js')?>"></script>
    <script src="<?=base_url('public/js/pages/chat-popup.js')?>"></script>
    <script src="<?=base_url('public/assets/icons/feather-icons/feather.min.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <!--validation JS-->
    <script src="<?=base_url("public/js/pages/validation.js")?>"></script>
    <script src=" <?=base_url("public/js/pages/form-validation.js")?>"></script>

    <script src="<?=base_url("public/assets/vendor_components/PACE/pace.min.js")?>"></script>
    <script src="<?=base_url('public/custom/js/auth.js')?>"></script>

</body>

</html>