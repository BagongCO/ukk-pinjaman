<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "db_peminjaman_ps";

// Membuat koneksi ke database
$connect = mysqli_connect($host, $username, $password, $dbName);

// Memeriksa apakah koneksi berhasil
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
