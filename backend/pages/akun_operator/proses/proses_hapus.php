<?php
include '../../../app.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../auth/index.php");
    exit;
}

/* ===============================
   AMBIL ID USER
================================ */
$id_user = intval($_POST['id_user']);

/* ===============================
   CEGAH HAPUS AKUN SENDIRI
================================ */
if ($id_user == $_SESSION['id_user']) {
    $_SESSION['error'] = "Anda tidak bisa menghapus akun sendiri!";
    header("Location: ../index.php");
    exit;
}

/* ===============================
   HAPUS DATA
================================ */
$query = mysqli_query($connect, "DELETE FROM users WHERE id_user='$id_user'");

if ($query) {
    /* ===============================
       LOG AKTIVITAS
    ================================ */
    mysqli_query($connect, "
        INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu)
        VALUES ('{$_SESSION['id_user']}', '{$_SESSION['username']}', '{$_SESSION['role']}', 'Menghapus akun id=$id_user', NOW())
    ");

    $_SESSION['success'] = "Akun berhasil dihapus";
} else {
    $_SESSION['error'] = "Gagal menghapus akun : " . mysqli_error($connect);
}

header("Location: ../index.php");
exit;
?>