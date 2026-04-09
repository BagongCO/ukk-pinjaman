<?php
session_start();
include '../../app.php';

if (isset($_POST['tombol'])) {
    $id_user = intval($_POST['id_user']);
    $id_barang = intval($_POST['id_barang']);
    $tanggal_pinjam = mysqli_real_escape_string($connect, $_POST['tanggal_pinjam']);
    $jam_pinjam = mysqli_real_escape_string($connect, $_POST['jam_pinjam']);
    $durasi_jam = intval($_POST['durasi_jam']);
    
    if ($id_user <= 0 || $id_barang <= 0 || empty($tanggal_pinjam) || empty($jam_pinjam) || $durasi_jam <= 0) {
        $_SESSION['error'] = "Semua data wajib diisi dengan benar!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    // Ambil harga barang
    $queryHarga = mysqli_query($connect, "SELECT harga_per_jam FROM barang WHERE id_barang = '$id_barang'");
    if (mysqli_num_rows($queryHarga) == 0) {
        $_SESSION['error'] = "Barang tidak ditemukan!";
        header("Location: ../../pages/peminjaman/index.php");
        exit;
    }
    
    $dataBarang = mysqli_fetch_assoc($queryHarga);
    $harga_per_jam = $dataBarang['harga_per_jam'];
    
    // Hitung tanggal dan jam kembali
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . " + $durasi_jam hours"));
    $jam_kembali = date('H:i:s', strtotime($jam_pinjam . " + $durasi_jam hours"));
    $total_harga = $durasi_jam * $harga_per_jam;
    
    $queryInsert = "INSERT INTO peminjaman 
                    (id_user, id_barang, tanggal_pinjam, jam_pinjam, tanggal_kembali, jam_kembali, durasi_jam, total_harga, status, created_at) 
                    VALUES 
                    ('$id_user', '$id_barang', '$tanggal_pinjam', '$jam_pinjam', '$tanggal_kembali', '$jam_kembali', '$durasi_jam', '$total_harga', 'dipinjam', NOW())";
    
    if (mysqli_query($connect, $queryInsert)) {
        $_SESSION['success'] = "Peminjaman berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan: " . mysqli_error($connect);
    }
    header("Location: ../../pages/peminjaman/index.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../pages/peminjaman/index.php");
    exit;
}
?>