
<?php

// ==========================================
// KONEKSI DATABASE
// ==========================================

$conn = mysqli_connect("localhost", "root", "", "toko_bolu");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}


// ==========================================
// FUNGSI MENAMPILKAN DATA
// ==========================================

function query_db($query) {
    global $conn;

    $result = mysqli_query($conn, $query);
    $rows = [];

    if (!$result) {
        return $rows;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}


// ==========================================
// FUNGSI PERHITUNGAN HARGA BOLU
// ==========================================

function hitung_harga_bolu($nama_bolu, $jumlah) {

    if ($nama_bolu == "Bolu Kukus Pandan") {
        $harga_satuan = 35000;

    } elseif ($nama_bolu == "Bolu Meranti Keju") {
        $harga_satuan = 65000;

    } elseif ($nama_bolu == "Bolu Susu Lembang") {
        $harga_satuan = 40000;

    } elseif ($nama_bolu == "Bolu Pisang Coklat") {
        $harga_satuan = 45000;

    } elseif ($nama_bolu == "Bolu Karamel") {
        $harga_satuan = 50000;

    } elseif ($nama_bolu == "Bolu Lapis Talas") {
        $harga_satuan = 48000;

    } else {
        $harga_satuan = 0;
    }

    $total = $harga_satuan * (int)$jumlah;

    return "Rp " . number_format($total, 0, ",", ".");
}


// ==========================================
// FUNGSI UPLOAD GAMBAR
// ==========================================

function upload_gambar($nama_pembeli = "", $nama_bolu = "") {

    if (!isset($_FILES["gambar"])) {
        return false;
    }

    $namaFile = $_FILES["gambar"]["name"];
    $ukuranFile = $_FILES["gambar"]["size"];
    $error = $_FILES["gambar"]["error"];
    $tmpName = $_FILES["gambar"]["tmp_name"];

    // Mengecek gambar
    if ($error === 4) {
        echo "<script>
            alert('Silahkan pilih gambar terlebih dahulu!');
        </script>";

        return false;
    }

    // Mengecek ekstensi gambar
    $ekstensiValid = ["jpg", "jpeg", "png"];

    $ekstensiGambar = explode(".", $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiValid)) {
        echo "<script>
            alert('File harus berupa JPG, JPEG, atau PNG!');
        </script>";

        return false;
    }

    // Mengecek ukuran gambar maksimal 5 MB
    if ($ukuranFile > 5000000) {
        echo "<script>
            alert('Ukuran gambar terlalu besar!');
        </script>";

        return false;
    }

    // Membuat nama gambar baru
    $namaFileBaru = uniqid("bolu_") . "." . $ekstensiGambar;

    // Lokasi folder gambar
    $folder = "img/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // Memindahkan gambar
    if (move_uploaded_file($tmpName, $folder . $namaFileBaru)) {
        return $namaFileBaru;
    }

    return false;
}


// ==========================================
// MANAJEMEN KASIR
// ==========================================


// TAMBAH DATA KASIR

function tambah_data($data) {
    global $conn;

    $nama_pembeli = mysqli_real_escape_string($conn, $data["nama_pembeli"]);
    $nama_bolu = mysqli_real_escape_string($conn, $data["nama_bolu"]);

    $jumlah_angka = (int)$data["jumlah"];

    if ($jumlah_angka < 1) {
        return false;
    }

    $jumlah = $jumlah_angka . " Box";

    // Menghitung harga otomatis
    $harga = hitung_harga_bolu($nama_bolu, $jumlah_angka);

    // Upload gambar
    $gambar = upload_gambar($nama_pembeli, $nama_bolu);

    if (!$gambar) {
        return false;
    }

    mysqli_begin_transaction($conn);

    // Memasukkan data ke tabel kasir
    $query = "INSERT INTO kasir
              (nama_pembeli, nama_bolu, jumlah, harga, gambar)
              VALUES
              ('$nama_pembeli', '$nama_bolu', '$jumlah', '$harga', '$gambar')";

    if (!mysqli_query($conn, $query)) {
        mysqli_rollback($conn);
        return false;
    }

    // Mengambil ID kasir
    $id_kasir = mysqli_insert_id($conn);

    // ID Dapur dibuat sama dengan ID Kasir
    $query_dapur = "INSERT INTO dapur
                    (id, nama_pembeli, nama_bolu, jumlah,
                    status_dapur, id_kasir)
                    VALUES
                    ('$id_kasir', '$nama_pembeli', '$nama_bolu',
                    '$jumlah', 'Menunggu Diproses', '$id_kasir')";

    if (!mysqli_query($conn, $query_dapur)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);

    return 1;
}


// EDIT DATA KASIR

function edit_data($data) {
    global $conn;

    $id = (int)$data["id"];

    $nama_pembeli = mysqli_real_escape_string($conn, $data["nama_pembeli"]);
    $nama_bolu = mysqli_real_escape_string($conn, $data["nama_bolu"]);

    $jumlah_angka = (int)$data["jumlah"];

    if ($jumlah_angka < 1) {
        return false;
    }

    $jumlah = $jumlah_angka . " Box";

    // Mengambil data lama
    $data_lama = query_db("SELECT * FROM kasir WHERE id = $id");

    if (count($data_lama) == 0) {
        return false;
    }

    // Menghitung harga baru
    $harga = hitung_harga_bolu($nama_bolu, $jumlah_angka);

    // Menggunakan gambar lama
    $gambar = $data_lama[0]["gambar"];

    // Mengecek apakah ada gambar baru
    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] != 4) {

        $gambar_baru = upload_gambar($nama_pembeli, $nama_bolu);

        if (!$gambar_baru) {
            return false;
        }

        $gambar = $gambar_baru;
    }

    mysqli_begin_transaction($conn);

    // Memperbarui tabel kasir
    $query = "UPDATE kasir SET
              nama_pembeli = '$nama_pembeli',
              nama_bolu = '$nama_bolu',
              jumlah = '$jumlah',
              harga = '$harga',
              gambar = '$gambar'
              WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
        mysqli_rollback($conn);
        return false;
    }

    // Mengecek data dapur berdasarkan ID yang sama
    $cek_dapur = mysqli_query(
        $conn,
        "SELECT id FROM dapur WHERE id = $id"
    );

    if (mysqli_num_rows($cek_dapur) > 0) {

        // Memperbarui data dapur
        // Status dapur tetap dipertahankan

        $query_dapur = "UPDATE dapur SET
                        nama_pembeli = '$nama_pembeli',
                        nama_bolu = '$nama_bolu',
                        jumlah = '$jumlah',
                        id_kasir = '$id'
                        WHERE id = $id";

    } else {

        // Membuat data dapur jika belum tersedia

        $query_dapur = "INSERT INTO dapur
                        (id, nama_pembeli, nama_bolu, jumlah,
                        status_dapur, id_kasir)
                        VALUES
                        ('$id', '$nama_pembeli', '$nama_bolu',
                        '$jumlah', 'Menunggu Diproses', '$id')";
    }

    if (!mysqli_query($conn, $query_dapur)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);

    return 1;
}


// HAPUS DATA KASIR

function hapus_data($id) {
    global $conn;

    $id = (int)$id;

    mysqli_begin_transaction($conn);

    // Menghapus data dapur berdasarkan ID yang sama
    $query_dapur = "DELETE FROM dapur WHERE id = $id";

    if (!mysqli_query($conn, $query_dapur)) {
        mysqli_rollback($conn);
        return false;
    }

    // Menghapus data kasir
    $query = "DELETE FROM kasir WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);

    return 1;
}


// PENCARIAN DATA KASIR

function cari_data($keyword) {
    global $conn;

    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = "SELECT * FROM kasir
              WHERE nama_pembeli LIKE '%$keyword%'
              OR nama_bolu LIKE '%$keyword%'
              OR jumlah LIKE '%$keyword%'
              OR harga LIKE '%$keyword%'";

    return query_db($query);
}


// ==========================================
// MANAJEMEN DAPUR
// ==========================================


// TAMBAH DATA DAPUR

function tambah_dapur($data) {
    global $conn;

    $nama_pembeli = mysqli_real_escape_string($conn, $data["nama_pembeli"]);
    $nama_bolu = mysqli_real_escape_string($conn, $data["nama_bolu"]);
    $status_dapur = mysqli_real_escape_string($conn, $data["status_dapur"]);

    $jumlah_angka = (int)$data["jumlah"];
    if ($jumlah_angka < 1) {
        $jumlah_angka = 1;
    }
    $jumlah = $jumlah_angka . " Box";
    $harga = hitung_harga_bolu($nama_bolu, $jumlah_angka);

    // Pilih gambar bawaan sesuai nama bolu
    $daftar_gambar = [
        "Bolu Kukus Pandan" => "bolu_kukus_pandan.png",
        "Bolu Meranti Keju" => "bolu_meranti_keju.png",
        "Bolu Susu Lembang" => "bolu_susu_lembang.png",
        "Bolu Pisang Coklat" => "bolu_pisang_coklat.png",
        "Bolu Karamel" => "bolu_karamel.png",
        "Bolu Lapis Talas" => "bolu_lapis_talas.png"
    ];
    $gambar = isset($daftar_gambar[$nama_bolu]) ? $daftar_gambar[$nama_bolu] : "bolu_kukus_pandan.png";

    mysqli_begin_transaction($conn);

    $query_kasir = "INSERT INTO kasir (nama_pembeli, nama_bolu, jumlah, harga, gambar)
                    VALUES ('$nama_pembeli', '$nama_bolu', '$jumlah', '$harga', '$gambar')";

    if (!mysqli_query($conn, $query_kasir)) {
        mysqli_rollback($conn);
        return false;
    }

    $id_kasir = mysqli_insert_id($conn);

    $query_dapur = "INSERT INTO dapur (id, nama_pembeli, nama_bolu, jumlah, status_dapur, id_kasir)
                    VALUES ('$id_kasir', '$nama_pembeli', '$nama_bolu', '$jumlah', '$status_dapur', '$id_kasir')";

    if (!mysqli_query($conn, $query_dapur)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return 1;
}


// EDIT DATA DAPUR

function edit_dapur($data) {
    global $conn;

    $id = (int)$data["id"];

    $nama_pembeli = mysqli_real_escape_string($conn, $data["nama_pembeli"]);
    $nama_bolu = mysqli_real_escape_string($conn, $data["nama_bolu"]);

    $jumlah_angka = (int)$data["jumlah"];

    if ($jumlah_angka < 1) {
        return false;
    }

    $jumlah = $jumlah_angka . " Box";

    $status_dapur = mysqli_real_escape_string($conn, $data["status_dapur"]);

    $query = "UPDATE dapur SET
              nama_pembeli = '$nama_pembeli',
              nama_bolu = '$nama_bolu',
              jumlah = '$jumlah',
              status_dapur = '$status_dapur'
              WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
        return false;
    }

    return 1;
}


// HAPUS DATA DAPUR

function hapus_dapur($id) {
    global $conn;

    $id = (int)$id;

    // Menghapus data dapur saja
    // Data kasir tetap tersimpan

    $query = "DELETE FROM dapur WHERE id = $id";

    if (!mysqli_query($conn, $query)) {
        return false;
    }

    return 1;
}


// PENCARIAN DATA DAPUR

function cari_dapur($keyword) {
    global $conn;

    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = "SELECT * FROM dapur
              WHERE nama_pembeli LIKE '%$keyword%'
              OR nama_bolu LIKE '%$keyword%'
              OR jumlah LIKE '%$keyword%'
              OR status_dapur LIKE '%$keyword%'";

    return query_db($query);
}


// ==========================================
// REGISTRASI ADMIN
// ==========================================

function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));

    $password = mysqli_real_escape_string($conn, $data["password"]);

    $konfirmasi_password = mysqli_real_escape_string(
        $conn,
        $data["konfirmasi_password"]
    );

    // Mengecek username
    $result = mysqli_query(
        $conn,
        "SELECT username FROM admin WHERE username = '$username'"
    );

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
            alert('Username sudah terdaftar!');
        </script>";

        return false;
    }

    // Mengecek konfirmasi password
    if ($password !== $konfirmasi_password) {
        echo "<script>
            alert('Konfirmasi password tidak sesuai!');
        </script>";

        return false;
    }

    // Mengenkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Memasukkan data admin
    $query = "INSERT INTO admin (username, password)
              VALUES ('$username', '$password')";

    if (!mysqli_query($conn, $query)) {
        return false;
    }

    return 1;
}

?>
