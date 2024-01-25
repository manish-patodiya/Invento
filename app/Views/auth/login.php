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
    style="background-image: url(<?=base_url('public/images/auth-bg/bg-' . rand(1, 12) . '.jpg');?>)">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Login</h2>
                                <p class="mb-0">Sign in to continue to <?php echo SITE_NAME; ?>.</p>
                            </div>
                            <div class="p-40">
                                <div class="alert alert-danger" id="login-err" style="display: none;"></div>
                                <form method="post" autocomplete="off" id="frm-login" onsubmit="return false">
                                    <div class="form-group">
                                        <div class="controls">
                                            <div class="input-group">
                                                <span class="input-group-text bg-transparent"><i
                                                        class="ti-user"></i></span>
                                                <input name="username" type="text"
                                                    class="form-control ps-15 bg-transparent" placeholder="Username"
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls">
                                            <div class="input-group">
                                                <span class="input-group-text  bg-transparent"><i
                                                        class="ti-lock"></i></span>
                                                <input type="password" name="password"
                                                    class="form-control ps-15 bg-transparent" placeholder="Password"
                                                    data-validation-required-message="This field is required" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_1">
                                                <label for="basic_checkbox_1">Remember Me</label>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-6">
                                            <div class="fog-pwd text-end">
                                                <a href="<?php echo base_url('auth/forgot_password') ?>"
                                                    class="hover-warning"><i class="ion ion-locked"></i> Forgot
                                                    pwd?</a><br>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-danger mt-10" id="btn-login">SIGN
                                                IN</button>
                                        </div>

                                        <!-- /.col -->
                                    </div>
                                </form>
                                <div class="text-center">
                                    <p class="mt-15 mb-0">Don't have an account? <a href="auth_register.html"
                                            class="text-warning ms-5">Sign Up</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="mt-20 text-white">- Sign With -</p>
                            <p class="gap-items-2 mb-20">
                                <a class="btn btn-social-icon btn-round btn-facebook" href="#"><i
                                        class="fa fa-facebook"></i></a>
                                <a class="btn btn-social-icon btn-round btn-twitter" href="#"><i
                                        class="fa fa-twitter"></i></a>
                                <a class="btn btn-social-icon btn-round btn-instagram" href="#"><i
                                        class="fa fa-instagram"></i></a>
                            </p>
                        </div>
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