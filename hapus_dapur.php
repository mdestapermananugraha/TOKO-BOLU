<?php 
    require 'function.php';

    $id = $_GET["id"];

    if(hapus_dapur($id) > 0) {
        echo "<script>
        alert('Data dapur sudah berhasil dihapus dari basis data');
        document.location.href = 'dapur.php';
        </script>";
    } else {
        echo "<script>
        alert('Data dapur gagal dihapus dari basis data');
        </script>";
    }

?>
