<?php
include '../../../app.php';

/* =========================
AMBIL DATA
========================= */

$nama_barang   = mysqli_real_escape_string($connect, $_POST['nama_barang']);
$harga_per_jam = mysqli_real_escape_string($connect, $_POST['harga_per_jam']);
$stok          = mysqli_real_escape_string($connect, $_POST['stok']);

$upload_dir = "../../../../storage/barang/";

/* =========================
CEK FOLDER
========================= */

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

/* =========================
UPLOAD FOTO
========================= */

$foto_name = $_FILES['foto']['name'];
$tmp_file  = $_FILES['foto']['tmp_name'];

$ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

$allowed = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($ext, $allowed)) {
    die("Format gambar tidak diperbolehkan!");
}

$nama_baru = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $foto_name);

if (move_uploaded_file($tmp_file, $upload_dir . $nama_baru)) {

    mysqli_query($connect, "
INSERT INTO barang
(nama_barang,harga_per_jam,stok,foto)
VALUES
('$nama_barang','$harga_per_jam','$stok','$nama_baru')
");

    $_SESSION['success'] = "Barang berhasil ditambahkan";
} else {

    $_SESSION['error'] = "Upload foto gagal";
}

header("Location: ../index.php");
exit;
