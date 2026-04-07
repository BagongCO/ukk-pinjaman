<?php
session_start();
include "../app.php";

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];

$id_role = 3; // role peminjam

// cek username sudah ada
$cek = mysqli_query($connect, "SELECT * FROM users WHERE username='$username'");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
alert('Username sudah digunakan!');
window.location='register.php';
</script>";

    exit;
}

// simpan ke database
mysqli_query($connect, "
INSERT INTO users
(nama,username,password,id_role,created_at)
VALUES
('$nama','$username','$password','$id_role',NOW())
");

echo "<script>
alert('Register berhasil! Silahkan login');
window.location='login.php';
</script>";
