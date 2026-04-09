<?php
include '../../app.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID tidak valid'); window.location.href='../../pages/peminjaman/index.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

$qSelect = "SELECT 
                p.*,
                u.username,
                u.nama_lengkap,
                b.nama_barang,
                b.harga_per_jam
            FROM peminjaman p
            LEFT JOIN users u ON p.id_user = u.id_user
            LEFT JOIN barang b ON p.id_barang = b.id_barang
            WHERE p.id_peminjaman = $id";

$result = mysqli_query($connect, $qSelect) or die(mysqli_error($connect));
$peminjaman = mysqli_fetch_object($result);

if (!$peminjaman) {
    echo "<script>alert('Peminjaman tidak ditemukan'); window.location.href='../../pages/peminjaman/index.php';</script>";
    exit;
}
?>