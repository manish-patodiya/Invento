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
    style="background-image: url(<?=base_url('public/images/auth-bg/bg-4.jpg');?>)">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Change New Password</h2>
                                <p class="mb-0">Your new passowrd must be different from previous used passwords</p>
                            </div>
                            <div class="p-40">
                                <form method="post" autocomplete="off" id="reset_password" onsubmit="return false">
                                    <div class="form-group">
                                        <div class="input-group mb-3 ml-3">
                                            <span class="input-group-text  bg-transparent"><i
                                                    class="ti-lock"></i></span>
                                            <input type="text" name="password" class="form-control"
                                                placeholder="New password" id="user_email"
                                                data-validation-required-message="This field is required" />
                                        </div>
                                        <div class="form-control-feedback"><small>Must be at least 8 characters.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3 ml-3">
                                            <span class="input-group-text  bg-transparent"><i
                                                    class="ti-lock"></i></span>
                                            <input type="text" name="password" class="form-control"
                                                placeholder="Confirm Password" id="user_email"
                                                data-validation-required-message="This field is required" />
                                        </div>
                                        <input type="text" class="d-none" value="<?php echo $token ?>" name="user_id">
                                    </div>

                                    <!-- /.col -->
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-danger mt-10" id="change_password">Change
                                            Password</button>
                                    </div>
                                </form>
                            </div>
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


    <script src="<?=base_url("public/js/pages/validation.js")?>"></script>
    <script src=" <?=base_url("public/js/pages/form-validation.js")?>"></script>



    <script src="<?=base_url("public/assets/vendor_components/PACE/pace.min.js")?>"></script>
    <script src="<?=base_url('public/custom/js/forgot.js')?>"></script>
</body>

</html>