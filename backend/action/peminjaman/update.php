<?php
session_start();
include '../../app.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (isset($_POST['tombol'])) {
    $id_user = intval($_POST['id_user']);
    $id_barang = intval($_POST['id_barang']);
    $tanggal_pinjam = escapeString($_POST['tanggal_pinjam']);
    $jam_pinjam = escapeString($_POST['jam_pinjam']);
    $durasi_jam = intval($_POST['durasi_jam']);
    $status = escapeString($_POST['status']);
    
    // Validasi
    if ($id_user <= 0 || $id_barang <= 0 || empty($tanggal_pinjam) || empty($jam_pinjam) || $durasi_jam <= 0) {
        $_SESSION['error'] = "Semua data wajib diisi dengan benar!";
        header("Location: ../../pages/peminjaman/edit.php?id=$id");
        exit;
    }
    
    // Cek apakah peminjaman ada
    $checkId = mysqli_query($connect, "SELECT * FROM peminjaman WHERE id_peminjaman = $id");
    if (mysqli_num_rows($checkId) == 0) {
        $_SESSION['error'] = "Peminjaman tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    // Ambil harga barang
    $queryHarga = mysqli_query($connect, "SELECT harga_per_jam FROM barang WHERE id_barang = '$id_barang'");
    $dataBarang = mysqli_fetch_assoc($queryHarga);
    $harga_per_jam = $dataBarang['harga_per_jam'];
    
    // Hitung tanggal dan jam kembali
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . " + $durasi_jam hours"));
    $jam_kembali = date('H:i:s', strtotime($jam_pinjam . " + $durasi_jam hours"));
    $total_harga = $durasi_jam * $harga_per_jam;
    
    // Update data
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
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    } else {
        $_SESSION['error'] = "Gagal mengupdate: " . mysqli_error($connect);
        header("Location: ../../pages/peminjaman/edit.php?id=$id");
        exit;
    }
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>