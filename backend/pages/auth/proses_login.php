<?php
session_start();

include '../../app.php';

$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($connect, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if ($data) {

    if ($password == $data['password']) {

        // SESSION LOGIN
        $_SESSION['login'] = true;

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role_id'] = $data['id_role'];

        if ($data['id_role'] == 1) {
            $_SESSION['role'] = 'admin';
        } elseif ($data['id_role'] == 2) {
            $_SESSION['role'] = 'petugas';
        } elseif ($data['id_role'] == 3) {
            $_SESSION['role'] = 'peminjam';
        }

        header("Location: ../dashboard/index.php");
        exit;
    } else {
        echo "<script>alert('Password salah');window.location='login.php';</script>";
    }
} else {
    echo "<script>alert('Username tidak ditemukan');window.location='login.php';</script>";
}
