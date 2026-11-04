<?php
session_start();
require 'function.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['session_login'] = true;
            $_SESSION['username']      = $username;

            header('Location: dashboard.php');
            exit;
        } else {
            echo "<script>alert('Password yang kamu masukkan salah!');</script>";
        }
    } else {
        echo "<script>alert('Username yang kamu masukkan salah atau tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Bolu</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="text-center mb-4">Login Toko Bolu</h4>

            <form action="" method="post">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <a href="registrasi.php" class="btn btn-secondary">Registrasi</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
