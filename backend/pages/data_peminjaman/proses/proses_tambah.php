<?php
include '../../../app.php';

session_start();

/* ===============================
   CEK APAKAH TOMBOL DISUBMIT
================================ */
if (isset($_POST['tombol'])) {
    
    /* ===============================
       AMBIL DATA DARI FORM
    ================================= */
    $id_user = intval($_POST['id_user']);
    $id_barang = intval($_POST['id_barang']);
    $tanggal_pinjam = escapeString($_POST['tanggal_pinjam']);
    $jam_pinjam = escapeString($_POST['jam_pinjam']);
    $durasi_jam = intval($_POST['durasi_jam']);
    
    /* ===============================
       VALIDASI DATA
    ================================= */
    // Validasi tidak boleh kosong
    if (empty($id_user) || empty($id_barang) || empty($tanggal_pinjam) || empty($jam_pinjam) || empty($durasi_jam)) {
        $_SESSION['error'] = "Semua data wajib diisi!";
        header("Location: ../tambah.php");
        exit;
    }
    
    // Validasi durasi minimal 1 jam
    if ($durasi_jam < 1) {
        $_SESSION['error'] = "Durasi sewa minimal 1 jam!";
        header("Location: ../tambah.php");
        exit;
    }
    
    /* ===============================
       AMBIL HARGA BARANG
    ================================= */
    $queryHarga = mysqli_query($connect, "SELECT harga_per_jam FROM barang WHERE id_barang = '$id_barang'");
    
    if (mysqli_num_rows($queryHarga) == 0) {
        $_SESSION['error'] = "Barang tidak ditemukan!";
        header("Location: ../tambah.php");
        exit;
    }
    
    $dataBarang = mysqli_fetch_assoc($queryHarga);
    $harga_per_jam = $dataBarang['harga_per_jam'];
    
    /* ===============================
       HITUNG TANGGAL DAN JAM KEMBALI
    ================================= */
    $tanggal_kembali = date('Y-m-d', strtotime($tanggal_pinjam . " + $durasi_jam hours"));
    $jam_kembali = date('H:i:s', strtotime($jam_pinjam . " + $durasi_jam hours"));
    
    /* ===============================
       HITUNG TOTAL HARGA
    ================================= */
    $total_harga = $durasi_jam * $harga_per_jam;
    
    /* ===============================
       CEK APAKAH BARANG TERSEDIA
       (Cek stok barang jika ada kolom jumlah_tersedia)
    ================================= */
    // Cek apakah ada kolom jumlah_tersedia di tabel barang
    $cekKolom = mysqli_query($connect, "SHOW COLUMNS FROM barang LIKE 'jumlah_tersedia'");
    if (mysqli_num_rows($cekKolom) > 0) {
        $queryStok = mysqli_query($connect, "SELECT jumlah_tersedia FROM barang WHERE id_barang = '$id_barang'");
        $stok = mysqli_fetch_assoc($queryStok);
        
        if ($stok['jumlah_tersedia'] < 1) {
            $_SESSION['error'] = "Maaf, barang sedang tidak tersedia!";
            header("Location: ../tambah.php");
            exit;
        }
    }
    
    /* ===============================
       INSERT DATA PEMINJAMAN
    ================================= */
    $queryInsert = "INSERT INTO peminjaman 
                    (id_user, id_barang, tanggal_pinjam, jam_pinjam, tanggal_kembali, jam_kembali, durasi_jam, total_harga, status, created_at) 
                    VALUES 
                    ('$id_user', '$id_barang', '$tanggal_pinjam', '$jam_pinjam', '$tanggal_kembali', '$jam_kembali', '$durasi_jam', '$total_harga', 'dipinjam', NOW())";
    
    if (mysqli_query($connect, $queryInsert)) {
        
        /* ===============================
           UPDATE JUMLAH TERSEDIA (JIKA ADA)
        ================================= */
        if (mysqli_num_rows($cekKolom) > 0) {
            mysqli_query($connect, "UPDATE barang SET jumlah_tersedia = jumlah_tersedia - 1 WHERE id_barang = '$id_barang'");
        }
        
        /* ===============================
           LOG AKTIVITAS (JIKA TABEL ADA)
        ================================= */
        $cekLog = mysqli_query($connect, "SHOW TABLES LIKE 'log_aktivitas'");
        if (mysqli_num_rows($cekLog) > 0) {
            $aktivitas = "Menambahkan peminjaman barang ID: $id_barang, durasi: $durasi_jam jam, total: Rp " . number_format($total_harga);
            mysqli_query($connect, "INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu) 
                                    VALUES ('{$_SESSION['id_user']}', '{$_SESSION['username']}', '{$_SESSION['role']}', '$aktivitas', NOW())");
        }
        
        $_SESSION['success'] = "Data peminjaman berhasil ditambahkan!";
        header("Location: ../index.php");
        exit;
        
    } else {
        $_SESSION['error'] = "Gagal menambahkan data peminjaman: " . mysqli_error($connect);
        header("Location: ../tambah.php");
        exit;
    }
    
} else {
    // Jika tidak ada tombol yang ditekan
    $_SESSION['error'] = "Akses tidak sah!";
    header("Location: ../index.php");
    exit;
}
?>