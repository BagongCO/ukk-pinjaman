<?php
include '../../../app.php';

/* =========================
AMBIL DATA
========================= */

$id_barang     = $_POST['id_barang'];

$nama_barang   = mysqli_real_escape_string($connect, $_POST['nama_barang']);

$harga_per_jam = mysqli_real_escape_string($connect, $_POST['harga_per_jam']);

$stok          = mysqli_real_escape_string($connect, $_POST['stok']);

$foto_lama     = $_POST['foto_lama'];

$upload_dir = "../../../../storage/barang/";

/* =========================
CEK FOTO BARU
========================= */

if (!empty($_FILES['foto']['name'])) {

    $foto_name = $_FILES['foto']['name'];

    $tmp_file  = $_FILES['foto']['tmp_name'];

    $ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {

        die("Format gambar tidak diperbolehkan");
    }

    $nama_baru = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $foto_name);

    /* =========================
UPLOAD FOTO BARU
========================= */

    if (move_uploaded_file($tmp_file, $upload_dir . $nama_baru)) {

        /* HAPUS FOTO LAMA */

        if (!empty($foto_lama)) {

            $path_lama = $upload_dir . $foto_lama;

            if (file_exists($path_lama)) {

                unlink($path_lama);
            }
        }

        $query = mysqli_query($connect, "
UPDATE barang SET
nama_barang='$nama_barang',
harga_per_jam='$harga_per_jam',
stok='$stok',
foto='$nama_baru'
WHERE id_barang='$id_barang'
");
    } else {

        die("Upload gambar gagal");
    }
} else {

    /* =========================
TANPA GANTI FOTO
========================= */

    $query = mysqli_query($connect, "
UPDATE barang SET
nama_barang='$nama_barang',
harga_per_jam='$harga_per_jam',
stok='$stok'
WHERE id_barang='$id_barang'
");
}

if (!$query) {

    die("Query error : " . mysqli_error($connect));
}

$_SESSION['success'] = "Data berhasil diupdate";

header("Location: ../index.php");

exit;
