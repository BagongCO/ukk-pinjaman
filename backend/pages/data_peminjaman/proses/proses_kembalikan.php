<?php
include '../../../app.php';

$id_peminjaman = $_POST['id_peminjaman'];
$id_barang     = $_POST['id_barang'];

// update status peminjaman jadi dikembalikan
mysqli_query($connect, "
    UPDATE peminjaman 
    SET status = 'dikembalikan' 
    WHERE id_peminjaman = '$id_peminjaman'
");

// tambah stok barang +1
mysqli_query($connect, "
    UPDATE barang 
    SET stok = stok + 1 
    WHERE id_barang = '$id_barang'
");

$_SESSION['success'] = "Barang berhasil dikembalikan!";
header("Location: ../detail.php?id=$id_peminjaman");
exit;
