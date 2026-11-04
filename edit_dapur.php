<?php
session_start();

if (!isset($_SESSION['session_login'])) {
    header('Location: login.php');
    exit;
}

require 'function.php';

$id    = (int) $_GET["id"];
$dapur = query_db("SELECT * FROM dapur WHERE id = $id")[0];

if (isset($_POST['submit'])) {
    if (edit_dapur($_POST) > 0) {
        echo "<script>
            alert('Data dapur sudah berhasil diubah');
            document.location.href = 'dapur.php';
        </script>";
    } else {
        echo "<script>
            alert('Data dapur gagal diubah');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dapur</title>
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
        </ul>
    </nav>

    <!-- Sidebar AdminLTE -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="dashboard.php" class="brand-link">
            <span class="brand-text font-weight-light ml-3"><b>Toko Bolu</b> Admin</span>
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
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Manajemen Kasir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dapur.php" class="nav-link active">
                            <i class="nav-icon fas fa-utensils"></i>
                            <p>Manajemen Dapur</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper AdminLTE -->
    <div class="content-wrapper p-4">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h4 class="text-center mb-4">Edit Data Dapur</h4>

                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $dapur["id"]; ?>">

                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="form-control" value="<?php echo $dapur["nama_pembeli"]; ?>">
                    </div>

                    <div class="form-group">
                        <label>Pilih Bolu</label>
                        <select name="nama_bolu" class="form-control">
                            <option value="<?php echo $dapur["nama_bolu"]; ?>"><?php echo $dapur["nama_bolu"]; ?></option>
                            <option value="Bolu Kukus Pandan">Bolu Kukus Pandan</option>
                            <option value="Bolu Meranti Keju">Bolu Meranti Keju</option>
                            <option value="Bolu Susu Lembang">Bolu Susu Lembang</option>
                            <option value="Bolu Pisang Coklat">Bolu Pisang Coklat</option>
                            <option value="Bolu Karamel">Bolu Karamel</option>
                            <option value="Bolu Lapis Talas">Bolu Lapis Talas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="text" name="jumlah" class="form-control" value="<?php echo $dapur["jumlah"]; ?>">
                    </div>

                    <div class="form-group">
                        <label>Status Dapur</label>
                        <select name="status_dapur" class="form-control">
                            <option value="<?php echo $dapur["status_dapur"]; ?>"><?php echo $dapur["status_dapur"]; ?></option>
                            <option value="Menunggu Diproses">Menunggu Diproses</option>
                            <option value="Sedang Dibuat">Sedang Dibuat</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Ubah Data</button>
                    <a href="dapur.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
