<?php
session_start();
include '../../app.php';

if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Cek apakah peminjaman ada
    $check = mysqli_query($connect, "SELECT * FROM peminjaman WHERE id_peminjaman = $id");
    if (mysqli_num_rows($check) == 0) {
        $_SESSION['error'] = "Peminjaman tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    // Hapus data
    $qDelete = "DELETE FROM peminjaman WHERE id_peminjaman = $id";
    $result = mysqli_query($connect, $qDelete);
    
    if ($result) {
        $_SESSION['success'] = "Peminjaman berhasil dihapus!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal menghapus: " . mysqli_error($connect);
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "ID tidak ditemukan!";
    header("Location: 217/pages/peminjaman/index.php");
    exit;
}
?>