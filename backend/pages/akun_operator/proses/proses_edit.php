<?php
include '../../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_user = $_POST['id_user'];
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password'];
    $id_role = $_POST['id_role'];

    /* ===============================
       CEK PASSWORD DIISI ATAU TIDAK
    =============================== */

    if (!empty($password)) {

        // jika password diisi
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = mysqli_query($connect, "
            UPDATE users SET
                nama='$nama',
                username='$username',
                password='$password_hash',
                id_role='$id_role'
            WHERE id_user='$id_user'
        ");
    } else {

        // jika password kosong
        $query = mysqli_query($connect, "
            UPDATE users SET
                nama='$nama',
                username='$username',
                id_role='$id_role'
            WHERE id_user='$id_user'
        ");
    }

    if ($query) {

        $_SESSION['success'] = "Data berhasil diupdate";
    } else {

        $_SESSION['error'] = "Gagal mengupdate data";
    }

    header("Location: ../index.php");
    exit;
}
