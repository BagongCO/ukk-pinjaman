<?php
session_start();
include "../backend/app.php";

$id_user    = $_POST['id_user'];
$id_barang  = $_POST['id_barang'];
$durasi_jam = $_POST['durasi_jam'];

$tanggal_pinjam = date('Y-m-d');
$jam_pinjam     = date('H:i:s');

$qBarang = mysqli_query($connect, "SELECT stok, harga_per_jam FROM barang WHERE id_barang='$id_barang'");
$barang  = mysqli_fetch_assoc($qBarang);

$stok = $barang['stok'];
$harga_per_jam = $barang['harga_per_jam'];

if ($stok <= 0) {

    echo "<script>
alert('Stok PS sedang dipakai!');
window.location='index.php';
</script>";

    exit;
}

$total_harga = $durasi_jam * $harga_per_jam;

mysqli_query($connect, "
INSERT INTO peminjaman
(id_user,id_barang,tanggal_pinjam,jam_pinjam,durasi_jam,total_harga,status)
VALUES
('$id_user','$id_barang','$tanggal_pinjam','$jam_pinjam','$durasi_jam','$total_harga','dipinjam')
");

mysqli_query($connect, "
UPDATE barang
SET stok = stok - 1
WHERE id_barang='$id_barang'
");

echo "<script>
alert('Peminjaman berhasil!');
window.location='index.php';
</script>";
