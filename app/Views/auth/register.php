<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script>
    const BASE_URL = "<?php echo base_url(); ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
    .inputvalues input {
        border: 1px solid;
    }

    label.error {
        color: red;
    }

    .form-label {
        margin: 0 !important;
    }

    a {
        text-decoration: none;
    }

    .form-outline {
        text-align: start !important;
    }

    .form-control {
        border: 1px solid !important;
    }
    </style>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js">
    </script>
</head>


<body class="bg-light">
    <section style="display: block;" id="sec-register">
        <div class="position-fixed " style="right:30; bottom:30;">
            <img src="<?=base_url('public/img/appstore.png')?>" height="70" class="pointer" id="playstore">
            <img src="<?=base_url('public/img/playstore.png')?>" height="56" class="pointer" id="ios">
        </div>
        <div class="mask d-flex align-items-center h-100 ">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-light text-dark shadow my-5" style="border-radius: 15px;">
                            <div class="card-body text-center p-5 pt-4" id="card-register">
                                <a class="fs-4 text-dark " href="<?=base_url()?>"><img
                                        src="<?=base_url('public/img/Vishwakarma.jpg')?>"
                                        class="rounded-circle img-fluid" width="80" height="80"></a>
                                <h3 class="text-uppercase mb-3" style="margin-top:2rem">sign up</h3>
                                <form method="post" autocomplete="off" id="frm-register">
                                    <div class="form-outline mb-2 inputvalues"><label class="form-label">Phone
                                            no.:</label>
                                        <div class="m-0 row"><span class="col-2 p-2 input-group-text border-dark"
                                                style="margin-right: -15px;">+91</span><input name="phone" type="number"
                                                class="col border-start-0 rounded-0 rounded-end form-control" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col form-outline mb-2 inputvalues"><label class="form-label">First
                                                name:</label><input name="fname" type="text" class="form-control" />
                                        </div>
                                        <div class="col form-outline mb-2 inputvalues"><label class="form-label">Last
                                                name:</label><input name="lname" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-outline mb-2 inputvalues">
                                        <p class="m-0">
                                            <label class="label">Gotra/गोत्र:</label>
                                            <select class="selectpicker form-control text-dark" name="gotra" id="gotra"
                                                data-live-search="true" required>
                                                <option value="">Select your gotra</option>
                                                <?php foreach ($gotra as $value) {?>
                                                <option value="<?=$value->id?>"><?=$value->gotra?></option><?php }?>
                                            </select>
                                        </p>
                                    </div>
                                    <div class="form-outline mb-2 inputvalues"><label
                                            class=" form-label">Password:</label><input name="password" type="password"
                                            class="form-control" id="password" /></div>
                                    <div class="form-outline mb-3 inputvalues"><label class="form-label">Confirm
                                            password:</label><input name="cpassword" type="password"
                                            class="form-control" /></div>
                                    <div id="validation-err" class="alert alert-danger" style="display:none"></div>
                                    <div class="d-flex justify-content-center"><button type="submit"
                                            class="btn-lg btn-dark form-control" id="btn-register">Sign Up
                                        </button>
                                    </div>
                                    <p class="text-center text-dark mt-5 mb-0">Have already an account?
                                        <a href=<?php echo base_url(); ?>><u class="fw-bold">Login here</u></a>
                                    </p>
                                </form>
                            </div>
                            <?=view('auth/verifyOTP')?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="<?=base_url('public/js/auth.js')?>"></script>
    <script src="<?=base_url('public/js/validation_functions.js')?>"></script>
</body>

</html>