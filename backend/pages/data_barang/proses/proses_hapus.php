<?php
include '../../../app.php';

$id_barang = $_POST['id_barang'];

$upload_dir = "../../../../storage/barang/";

/* =========================
AMBIL FOTO
========================= */

$data = mysqli_query($connect, "
SELECT foto
FROM barang
WHERE id_barang='$id_barang'
");

$row = mysqli_fetch_assoc($data);

/* =========================
HAPUS FOTO
========================= */

if (!empty($row['foto'])) {

    $path = $upload_dir . $row['foto'];

    if (file_exists($path)) {

        unlink($path);
    }
}

/* =========================
HAPUS DATA
========================= */

$query = mysqli_query($connect, "
DELETE FROM barang
WHERE id_barang='$id_barang'
");

if (!$query) {

    die("Query error : " . mysqli_error($connect));
}

$_SESSION['success'] = "Barang berhasil dihapus";

header("Location: ../index.php");

exit;
