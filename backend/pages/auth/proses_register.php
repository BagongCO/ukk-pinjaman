<?php
session_start();

// Perbaiki path ke config (sesuaikan dengan struktur folder Anda)
include '../../../config/connection.php'; // Ganti dari connection.php ke koneksi.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$nama = mysqli_real_escape_string($connect, $_POST['nama']);
$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$id_role = 3; // LANGSUNG SET KE PEMINJAM

$errors = [];

// Validasi
if (empty($nama)) {
    $errors[] = "Nama lengkap harus diisi.";
}

if (empty($username)) {
    $errors[] = "Username harus diisi.";
} elseif (strlen($username) < 3) {
    $errors[] = "Username minimal 3 karakter.";
}

if (empty($password)) {
    $errors[] = "Password harus diisi.";
} elseif (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
} elseif ($password !== $confirm_password) {
    $errors[] = "Konfirmasi password tidak sama.";
}

// Cek username sudah ada
$check = mysqli_query($connect, "SELECT * FROM users WHERE username = '$username'");
if (mysqli_num_rows($check) > 0) {
    $errors[] = "Username '$username' sudah digunakan. Silakan pilih username lain.";
}

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: register.php");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Query insert
$query = "INSERT INTO users (nama, username, password, id_role, created_at) 
          VALUES ('$nama', '$username', '$hashed_password', $id_role, NOW())";

if (mysqli_query($connect, $query)) {
    $_SESSION['success'] = "Registrasi berhasil! Silakan login dengan username '$username'.";
    header("Location: login.php");
    exit;
} else {
    $_SESSION['error'] = "Gagal registrasi: " . mysqli_error($connect);
    header("Location: register.php");
    exit;
}
?>