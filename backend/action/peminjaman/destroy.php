<?php
session_start();
include '../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id_peminjaman'])) {
    $id = (int)$_POST['id_peminjaman'];
    
    $check = mysqli_query($connect, "SELECT * FROM peminjaman WHERE id_peminjaman = $id");
    if (mysqli_num_rows($check) == 0) {
        $_SESSION['error'] = "Peminjaman tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    $qDelete = "DELETE FROM peminjaman WHERE id_peminjaman = $id";
    $result = mysqli_query($connect, $qDelete);
    
    if ($result) {
        $_SESSION['success'] = "Peminjaman berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($connect);
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan!";
}

header("Location: ../../pages/peminjaman/index.php");
exit;
?>