<?php
echo view('include/header/header_top');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    .table>tbody>tr>td {
        padding-bottom: 0 !important;
        padding-top: 0 !important;
        vertical-align: top;
    }

    .d-none {
        display: none !important;
    }

    .footer {
        font-size: 9px;
        color: #00000;
        text-align: center;
    }

    .header {
        font-size: 9px;
        color: #00000;
        text-align: center;
    }

    @page {
        size: A4;
        /* margin: 0 17mm 0 17mm; */
        margin: 17mm 17mm 17mm 17mm;
    }

    html,
    body {
        width: 210mm;
        height: 297mm;
    }

    @media print {
        header {
            position: fixed;
            top: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0;
            background: white;
            width: 100%;
        }

        .content-block {
            page-break-inside: avoid;
            position: relative;
            width: 100%;
            top: 0px;
            bottom: 370px;
            /*match size of header*/
            left: 0px;
            right: 0px;

        }
    }
    </style>
</head>

<body>
    <?=$html?>
</body>

</html>