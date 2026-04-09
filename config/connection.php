<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "peminjaman_lampu";

// Membuat koneksi ke database
$connect = mysqli_connect($host, $username, $password, $dbName);

// Memeriksa apakah koneksi berhasil
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
