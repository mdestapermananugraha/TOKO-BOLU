<?php
session_start();

if (!isset($_SESSION['session_login'])) {
    header('Location: login.php');
    exit;
}

require 'function.php';

// Hitung jumlah pesanan berdasarkan status untuk kartu Dashboard AdminLTE
$pesananBaru    = count(query_db("SELECT * FROM dapur WHERE status_dapur = 'Menunggu Diproses'"));
$sedangDiproses = count(query_db("SELECT * FROM dapur WHERE status_dapur = 'Sedang Dibuat'"));
$pesananSelesai = count(query_db("SELECT * FROM dapur WHERE status_dapur = 'Selesai'"));
$totalPesanan   = count(query_db("SELECT * FROM kasir"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toko Bolu</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar AdminLTE -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="dashboard.php" class="nav-link">Hallo, selamat datang <?php echo $_SESSION['username']; ?></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar AdminLTE -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="dashboard.php" class="brand-link">
            <span class="brand-text font-weight-light ml-3"><strong>Toko Bolu</strong> Admin</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Manajemen Kasir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dapur.php" class="nav-link">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>Manajemen Dapur</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper AdminLTE -->
    <div class="content-wrapper p-4">
        <div class="container-fluid">
            <h3 class="mb-4">Dashboard Toko Bolu</h3>

            <!-- Baris Small Box AdminLTE -->
            <div class="row">
                <!-- Box 1: Pesanan Baru -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?php echo $pesananBaru; ?></h3>
                            <p>Pesanan Baru</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="dapur.php?status=Menunggu+Diproses" class="small-box-footer">
                            Informasi selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Box 2: Sedang Diproses -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $sedangDiproses; ?></h3>
                            <p>Sedang Diproses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <a href="dapur.php?status=Sedang+Dibuat" class="small-box-footer">
                            Informasi selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Box 3: Pesanan Selesai -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $pesananSelesai; ?></h3>
                            <p>Pesanan Selesai</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <a href="dapur.php?status=Selesai" class="small-box-footer">
                            Informasi selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Box 4: Total Pesanan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $totalPesanan; ?></h3>
                            <p>Total Pesanan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="index.php" class="small-box-footer">
                            Informasi selengkapnya <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
