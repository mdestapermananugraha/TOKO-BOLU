<?php
require 'function.php';

if (isset($_POST["register"])) {
    if (registrasi($_POST) > 0) {
        echo "<script>alert('User baru berhasil ditambahkan!');</script>";
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Toko Bolu</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="text-center mb-4">Registrasi Admin Toko Bolu</h4>

            <form action="" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="Masukkan kembali password" required>
                </div>

                <button type="submit" name="register" class="btn btn-primary">Registrasi</button>
                <a href="login.php" class="btn btn-secondary">Login</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
