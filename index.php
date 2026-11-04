<?php
session_start();

if (!isset($_SESSION['session_login'])) {
    header('Location: login.php');
    exit;
}

require 'function.php';

// Konfigurasi Pagination
$jumlahDataPerHalaman = 5;
$jumlahData           = count(query_db("SELECT * FROM kasir"));
$jumlahHalaman        = max(1, ceil($jumlahData / $jumlahDataPerHalaman));
$halamanAktif         = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;
$awalData             = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Ambil data kasir
$kasir = query_db("SELECT * FROM kasir LIMIT $awalData, $jumlahDataPerHalaman");

// Pencarian data kasir
if (isset($_POST["cari"])) {
    $kasir = cari_data($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kasir - Toko Bolu</title>
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
                        <a href="dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
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
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4">Daftar Transaksi Kasir Toko Bolu</h3>

                <!-- Baris Pencarian & Tombol Tambah -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <form action="" method="post" class="form-inline">
                        <input type="text" name="keyword" class="form-control mr-2" placeholder="Cari pesanan kasir...">
                        <button type="submit" name="cari" class="btn btn-primary">Cari</button>
                    </form>
                    <a href="tambah_data.php" class="btn btn-success">+ Tambah Data Pembeli</a>
                </div>

                <!-- Tabel Kasir AdminLTE -->
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Nama Pembeli</th>
                            <th>Nama Bolu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $nomor = 1; ?>
                        <?php foreach ($kasir as $data): ?>
                            <tr>
                                <td><?php echo $nomor++; ?></td>
                                <td><?php echo $data["id"]; ?></td>
                                <td><?php echo $data["nama_pembeli"]; ?></td>
                                <td><?php echo $data["nama_bolu"]; ?></td>
                                <td><?php echo $data["jumlah"]; ?></td>
                                <td><?php echo $data["harga"]; ?></td>
                                <td>
                                    <img src="img/<?php echo $data["gambar"]; ?>" alt="" width="80" height="80" class="rounded">
                                </td>
                                <td>
                                    <a href="edit_data.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="hapus_data.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination AdminLTE -->
                <ul class="pagination justify-content-center mt-3 mb-0">
                    <?php if ($halamanAktif > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?halaman=<?php echo $halamanAktif - 1; ?>">&larr; prev</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">&larr; prev</span></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                        <li class="page-item <?php if ($i == $halamanAktif) echo 'active'; ?>">
                            <a class="page-link" href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $jumlahHalaman): ?>
                        <li class="page-item">
                            <a class="page-link" href="?halaman=<?php echo $halamanAktif + 1; ?>">Next &rarr;</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled"><span class="page-link">Next &rarr;</span></li>
                    <?php endif; ?>
                </ul>

            </div>
        </div>
    </div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
