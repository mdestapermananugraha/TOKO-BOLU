<?php
session_start();

if (!isset($_SESSION['session_login'])) {
    header('Location: login.php');
    exit;
}

require 'function.php';

if (isset($_POST['submit'])) {
    if (tambah_data($_POST) > 0) {
        echo "<script>
            alert('Data sudah berhasil dimasukkan ke basis data');
            document.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Data gagal dimasukkan ke basis data');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kasir</title>
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
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper AdminLTE -->
    <div class="content-wrapper p-4">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h4 class="text-center mb-4">Tambah Data Kasir</h4>

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="form-control" placeholder="Masukkan nama pembeli" required>
                    </div>

                    <div class="form-group">
                        <label>Pilih Bolu</label>
                        <select name="nama_bolu" id="nama_bolu" class="form-control" onchange="hitungHargaOtomatis()" required>
                            <option value="Bolu Kukus Pandan" data-harga="35000">Bolu Kukus Pandan (Rp 35.000)</option>
                            <option value="Bolu Meranti Keju" data-harga="65000">Bolu Meranti Keju (Rp 65.000)</option>
                            <option value="Bolu Susu Lembang" data-harga="40000">Bolu Susu Lembang (Rp 40.000)</option>
                            <option value="Bolu Pisang Coklat" data-harga="45000">Bolu Pisang Coklat (Rp 45.000)</option>
                            <option value="Bolu Karamel" data-harga="50000">Bolu Karamel (Rp 50.000)</option>
                            <option value="Bolu Lapis Talas" data-harga="48000">Bolu Lapis Talas (Rp 48.000)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah (Box)</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" value="1" oninput="hitungHargaOtomatis()" required>
                    </div>

                    <div class="form-group">
                        <label>Total Harga (Otomatis)</label>
                        <input type="text" name="harga" id="harga" class="form-control" value="Rp 35.000" readonly>
                    </div>

                    <div class="form-group">
                        <label>Gambar Bolu</label>
                        <input type="file" name="gambar" class="form-control-file" accept=".jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">Format JPG, JPEG, PNG. Maksimal 5 MB.</small>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Tambah Data</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
function hitungHargaOtomatis() {
    var pilihBolu = document.getElementById("nama_bolu");
    var hargaSatuan = pilihBolu.options[pilihBolu.selectedIndex].getAttribute("data-harga");
    var jumlah = document.getElementById("jumlah").value;
    var totalHarga = hargaSatuan * jumlah;
    document.getElementById("harga").value = "Rp " + totalHarga.toLocaleString("id-ID");
}
</script>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
