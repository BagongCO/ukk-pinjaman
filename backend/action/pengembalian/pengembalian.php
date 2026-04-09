<?php
session_start();
include '../../app.php';

if (isset($_POST['tombol'])) {
    $id_peminjaman = intval($_POST['id_peminjaman']);
    $kondisi = mysqli_real_escape_string($connect, $_POST['kondisi']);
    $keterangan = mysqli_real_escape_string($connect, $_POST['keterangan']);
    $tanggal_kembali_real = date('Y-m-d');
    $jam_kembali_real = date('H:i:s');
    
    // Cek apakah peminjaman ada dan statusnya dipinjam
    $check = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.stok 
                                      FROM peminjaman p 
                                      LEFT JOIN barang b ON p.id_barang = b.id_barang 
                                      WHERE p.id_peminjaman = $id_peminjaman AND p.status = 'dipinjam'");
    
    if (mysqli_num_rows($check) == 0) {
        $_SESSION['error'] = "Peminjaman tidak ditemukan atau sudah dikembalikan!";
        header("Location: ../../pages/pengembalian/pengembalian.php");
        exit;
    }
    
    $data = mysqli_fetch_assoc($check);
    
    // Hitung denda jika telat
    $batas_kembali = $data['tanggal_kembali'] . ' ' . $data['jam_kembali'];
    $now = date('Y-m-d H:i:s');
    $denda = 0;
    $denda_per_jam = 5000;
    
    if (strtotime($now) > strtotime($batas_kembali)) {
        $selisih = strtotime($now) - strtotime($batas_kembali);
        $jam_telat = ceil($selisih / 3600);
        $denda = $jam_telat * $denda_per_jam;
    }
    
    // Update status peminjaman
    $update = "UPDATE peminjaman SET 
                status = 'dikembalikan',
                tanggal_kembali_real = '$tanggal_kembali_real',
                jam_kembali_real = '$jam_kembali_real',
                denda = '$denda',
                kondisi_barang = '$kondisi',
                keterangan_pengembalian = '$keterangan'
               WHERE id_peminjaman = $id_peminjaman";
    
    if (mysqli_query($connect, $update)) {
        // Update stok barang (kembalikan stok)
        $id_barang = $data['id_barang'];
        $update_stok = "UPDATE barang SET stok = stok + 1 WHERE id_barang = $id_barang";
        mysqli_query($connect, $update_stok);
        
        // ========== PERBAIKAN UTAMA: Escape semua string dengan mysqli_real_escape_string ==========
        $id_user = $_SESSION['id_user'];
        $username = mysqli_real_escape_string($connect, $_SESSION['username'] ?? 'Admin');
        $nama_barang = mysqli_real_escape_string($connect, $data['nama_barang']);
        $denda_format = number_format($denda, 0, ',', '.');
        
        // Gunakan CONCAT untuk menghindari masalah kutip
        $aktivitas = "Mengembalikan barang " . $nama_barang . " dengan denda Rp " . $denda_format;
        
        $log = "INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu) 
                VALUES ('$id_user', '$username', 'admin', '$aktivitas', NOW())";
        
        $result_log = mysqli_query($connect, $log);
        
        if (!$result_log) {
            // Jika gagal, set error tapi tidak mengganggu proses utama
            error_log("Gagal insert log: " . mysqli_error($connect));
        }
        
        $message = "Barang berhasil dikembalikan!";
        if ($denda > 0) {
            $message .= " Denda: Rp " . number_format($denda);
        }
        $_SESSION['success'] = $message;
    } else {
        $_SESSION['error'] = "Gagal mengembalikan: " . mysqli_error($connect);
    }
    
    header("Location: ../../pages/pengembalian/pengembalian.php");
    exit;
} else {
    $_SESSION['error'] = "Akses tidak valid!";
    header("Location: ../../pages/pengembalian/pengembalian.php");
    exit;
}
?>