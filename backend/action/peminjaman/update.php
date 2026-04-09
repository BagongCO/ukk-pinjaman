<?php
session_start();
include '../../app.php';

if (isset($_POST['tombol']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $id_user = intval($_POST['id_user']);
    $id_barang = intval($_POST['id_barang']);
    $tanggal_pinjam = mysqli_real_escape_string($connect, $_POST['tanggal_pinjam']);
    $jam_pinjam = mysqli_real_escape_string($connect, $_POST['jam_pinjam']);
    $durasi_jam = intval($_POST['durasi_jam']);
    $status = mysqli_real_escape_string($connect, $_POST['status']);
    
    if ($id_user <= 0 || $id_barang <= 0 || empty($tanggal_pinjam) || empty($jam_pinjam) || $durasi_jam <= 0) {
        $_SESSION['error'] = "Semua data wajib diisi dengan benar!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    $checkId = mysqli_query($connect, "SELECT * FROM peminjaman WHERE id_peminjaman = $id");
    if (mysqli_num_rows($checkId) == 0) {
        $_SESSION['error'] = "Peminjaman tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    $queryHarga = mysqli_query($connect, "SELECT harga_per_jam FROM barang WHERE id_barang = '$id_barang'");
    $dataBarang = mysqli_fetch_assoc($queryHarga);
    $harga_per_jam = $dataBarang['harga_per_jam'];
    
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . " + $durasi_jam hours"));
    $jam_kembali = date('H:i:s', strtotime($jam_pinjam . " + $durasi_jam hours"));
    $total_harga = $durasi_jam * $harga_per_jam;
    
    $qUpdate = "UPDATE peminjaman SET 
                    id_user = '$id_user',
                    id_barang = '$id_barang',
                    tanggal_pinjam = '$tanggal_pinjam',
                    jam_pinjam = '$jam_pinjam',
                    tanggal_kembali = '$tanggal_kembali',
                    jam_kembali = '$jam_kembali',
                    durasi_jam = '$durasi_jam',
                    total_harga = '$total_harga',
                    status = '$status'
                WHERE id_peminjaman = $id";
    
    if (mysqli_query($connect, $qUpdate)) {
        $_SESSION['success'] = "Peminjaman berhasil diupdate!";
    } else {
        $_SESSION['error'] = "Gagal mengupdate: " . mysqli_error($connect);
    }
    header("Location: ../../pages/peminjaman/index.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>