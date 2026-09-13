<?php
// =====================================================
// DPWH Aurora Materials Testing Repository System
// Header File
// =====================================================

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
 * IMPORTANT:
 * This keeps images and other files working even when
 * opening pages/functions inside different folders.
 */
$base_url = '/proposed_dpwh_repository';

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        DPWH Aurora Materials Testing Repository
    </title>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/fontawesome-free/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Toastr -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/toastr/toastr.min.css">

    <!-- Overlay Scrollbars -->
    <link rel="stylesheet"
          href="<?= $base_url ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

    <!-- Custom CSS -->
    <style>

        body{
            font-family:'Source Sans Pro',sans-serif;
        }

        .brand-link{
            text-align:center;
            font-weight:bold;
            font-size:18px;
        }

        .brand-link small{
            display:block;
            font-size:12px;
            color:#ddd;
        }

        .small-box{
            border-radius:15px;
        }

        .card{
            border-radius:10px;
        }

        .card-header{
            font-weight:bold;
        }

        .content-header h1{
            font-weight:bold;
        }

        .table th{
            background:#f4f6f9;
        }

        .badge{
            font-size:13px;
        }

        .user-panel img{
            width:35px;
            height:35px;
        }

        .login-logo b{
            color:#004a99;
        }

        .main-sidebar{
            background:#003366 !important;
        }

        .navbar{
            border-bottom:3px solid #ffc107;
        }

        .btn{
            border-radius:5px;
        }

        .content-wrapper{
            background:#f4f6f9;
        }

        .required{
            color:red;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">