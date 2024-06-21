<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo csrf_meta(); ?>
    <title>Dashboard</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome-free/css/all.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/datatables/DataTables/css/dataTables.bootstrap4.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('assets/datatables/Responsive/css/responsive.bootstrap4.min.css');?>">
    <link rel="stylesheet" href="<?= base_url('assets/toastify/toastify.css');?>">
    <link rel="stylesheet" href="<?= base_url('assets/select2/css/select2.min.css');?>">

    <style>
        .pristine-error{
            color:red;
        }
        .has-danger .form-control{
            border-color: red;
        }
        
		div.dataTables_wrapper {
			width: 100%;
			margin: 0 auto;
		}
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">Option</span>
                        <div class="dropdown-divider"></div>
                        <a href="<?= site_url('auth/logout'); ?>" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Dashboard</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <?php foreach($hak_akses_kode as $group):?>
                        <?= $this->include('menus/'.$group); ?>
                    <?php endforeach;?>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $h1 ?? '' ?></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">

                    <?= $this->renderSection('content'); ?>

                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url('assets/jquery/jquery.min.js'); ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets/adminlte/js/adminlte.min.js'); ?>"></script>

    <script src="<?php echo base_url('assets/datatables/DataTables/js/jquery.dataTables.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/DataTables/js/dataTables.bootstrap4.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/Responsive/js/dataTables.responsive.min.js');?>"></script>
    <script src="<?php echo base_url('assets/datatables/Responsive/js/responsive.bootstrap4.js');?>"></script>
    <script src="<?php echo base_url('assets/pristine/pristine.js');?>"></script>
    <script src="<?php echo base_url('assets/toastify/toastify.js');?>"></script>
    <script src="<?php echo base_url('assets/sweetalert2/sweetalert2.all.min.js');?>"></script>
    <script src="<?php echo base_url('assets/select2/js/select2.min.js');?>"></script>

    <script>
        'use strict';
        window.BASEURLWEB = "<?= base_url() ?>";
        window.SITEURLWEB = "<?= base_url() ?>";

        $('a.nav-link[href="'+window.location.href+'"]').addClass('active');

        $.ajaxSetup({
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'csrf_tok': $('meta[name=X-CSRF-TOKEN]').attr('content')
            }
        });

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <?= $this->renderSection('script'); ?>

</body>

</html>