<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=base_url('public/images/favicon.ico')?>">
    <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .form-control {
        background-color: white !important;
    }

    .mandatory {
        color: #fb5ea8;
    }

    .help-block>ul {
        margin: 0 !important;
    }
    </style>

    <title>Deposito Admin - Dashboard</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="<?=base_url('public/css/vendors_css.css')?>">

    <!-- Style-->
    <link rel="stylesheet" href="<?=base_url('public/css/style.css')?>">
    <link rel="stylesheet" href="<?=base_url('public/css/skin_color.css')?>">

    <link rel="stylesheet"
        href="<?=base_url('public/assets/vendor_components/bootstrap-duallistbox/bootstrap-duallistbox.min.css')?>">
    <script>
    const BASE_URL = "<?php echo base_url(); ?>";

    function base_url(uri) {
        return BASE_URL + uri;
    }
    </script>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
    <!-- <div id='loader'></div> -->