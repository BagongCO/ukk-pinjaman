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
   AMBIL DATA FORM
================================ */
$nama = escapeString($_POST['nama']);
$username = escapeString($_POST['username']);
$password = $_POST['password'];
$id_role = intval($_POST['id_role']);

/* ===============================
   VALIDASI INPUT
================================ */
if ($nama == "" || $username == "" || $password == "" || $id_role == "") {
    $_SESSION['error'] = "Semua data wajib diisi!";
    header("Location: ../index.php");
    exit;
}

/* ===============================
   CEK USERNAME DUPLIKAT
================================ */
$cek = mysqli_query($connect, "SELECT id_user FROM users WHERE username='$username'");

if (mysqli_num_rows($cek) > 0) {
    $_SESSION['error'] = "Username sudah digunakan!";
    header("Location: ../index.php");
    exit;
}

/* ===============================
   HASH PASSWORD
================================ */
$password_hash = password_hash($password, PASSWORD_DEFAULT);

/* ===============================
   INSERT DATA
================================ */
$query = mysqli_query($connect, "
    INSERT INTO users (nama, username, password, id_role)
    VALUES ('$nama','$username','$password_hash','$id_role')
");

if ($query) {

    /* ===============================
       LOG AKTIVITAS
    ================================ */
    mysqli_query($connect, "
        INSERT INTO log_aktivitas (id_user, username, role, aktivitas)
        VALUES ('{$_SESSION['id_user']}','{$_SESSION['username']}','{$_SESSION['role']}','Menambahkan akun operator')
    ");

    $_SESSION['success'] = "Akun berhasil ditambahkan";
} else {

    $_SESSION['error'] = "Gagal menambahkan akun : " . mysqli_error($connect);
}

header("Location: ../index.php");
exit;
