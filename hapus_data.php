<?php 
    require 'function.php';

    $id = $_GET["id"];

    if(hapus_data($id) > 0) {
        echo "<script>
        alert('Data sudah berhasil dihapus dari basis data');
        document.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
        alert('Data gagal dihapus dari basis data');
        </script>";
    }

?>
