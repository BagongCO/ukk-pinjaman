<?php
session_start();

// WAJIB LOGIN - Jika belum login, redirect ke halaman login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login");
    exit;
}
include "config/connection.php";

// Ambil data lampu terbaru (max 6)
$query_lampu = mysqli_query($connect, "SELECT * FROM barang WHERE stok > 0 ORDER BY id_barang DESC LIMIT 6");

// Hitung statistik
$total_lampu = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM barang"))['total'];
$total_jenis = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(DISTINCT id_barang) as total FROM barang"))['total'];
$total_sewa = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status='selesai'"))['total'];
$total_dipinjam = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];

include "frontend/partials/header.php";
include "frontend/partials/navbar.php";
?>

<?php include "frontend/home.php";?>

<?php include "frontend/lampu.php";?>

<?php include "frontend/about.php";?>

<?php include "frontend/contact.php";?>


<?php include "frontend/partials/footer.php"; ?>
<?php include "frontend/partials/script.php"; ?>