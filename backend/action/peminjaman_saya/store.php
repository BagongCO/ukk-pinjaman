<?php
session_start();
include '../../app.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../pages/auth/index.php");
    exit;
}

if ($_SESSION['role'] != 'peminjam') {
    header("Location: ../../pages/dashboard/index.php");
    exit;
}

if (isset($_POST['tombol'])) {
    $id_user = $_SESSION['id_user'];
    $id_barang = intval($_POST['id_barang']);
    $tanggal_pinjam = mysqli_real_escape_string($connect, $_POST['tanggal_pinjam']);
    $jam_pinjam = mysqli_real_escape_string($connect, $_POST['jam_pinjam']);
    $durasi_jam = intval($_POST['durasi_jam']);

    // Validasi
    if ($id_barang <= 0 || empty($tanggal_pinjam) || empty($jam_pinjam) || $durasi_jam <= 0) {
        $_SESSION['error'] = "Semua data wajib diisi dengan benar!";
        header("Location: ../../pages/peminjaman_saya/index.php");
        exit;
    }

    // Ambil harga barang dan stok
    $queryBarang = mysqli_query($connect, "SELECT harga_per_jam, stok FROM barang WHERE id_barang = $id_barang");
    if (mysqli_num_rows($queryBarang) == 0) {
        $_SESSION['error'] = "Barang tidak ditemukan!";
        header("Location: ../../pages/peminjaman_saya/index.php");
        exit;
    }
    $dataBarang = mysqli_fetch_assoc($queryBarang);
    $harga_per_jam = $dataBarang['harga_per_jam'];
    $stok = $dataBarang['stok'];

    // Cek stok cukup
    if ($stok <= 0) {
        $_SESSION['error'] = "Stok barang habis, tidak bisa dipinjam!";
        header("Location: ../../pages/peminjaman_saya/index.php");
        exit;
    }

    // Hitung tanggal dan jam kembali
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . " + $durasi_jam hours"));
    $jam_kembali = date('H:i:s', strtotime($jam_pinjam . " + $durasi_jam hours"));
    $total_harga = $durasi_jam * $harga_per_jam;

    // Insert ke tabel peminjaman
    $insert = "INSERT INTO peminjaman 
                (id_user, id_barang, tanggal_pinjam, jam_pinjam, tanggal_kembali, jam_kembali, durasi_jam, total_harga, status, created_at) 
                VALUES 
                ('$id_user', '$id_barang', '$tanggal_pinjam', '$jam_pinjam', '$tanggal_kembali', '$jam_kembali', '$durasi_jam', '$total_harga', 'dipinjam', NOW())";
    
    if (mysqli_query($connect, $insert)) {
        // Kurangi stok barang
        $updateStok = "UPDATE barang SET stok = stok - 1 WHERE id_barang = $id_barang";
        mysqli_query($connect, $updateStok);
        
        $_SESSION['success'] = "Peminjaman berhasil diajukan!";
    } else {
        $_SESSION['error'] = "Gagal meminjam: " . mysqli_error($connect);
    }
    
    header("Location: ../../pages/peminjaman_saya/index.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../pages/peminjaman_saya/index.php");
    exit;
}
?>