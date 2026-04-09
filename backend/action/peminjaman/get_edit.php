<?php
session_start();
include '../../app.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = (int)$_GET['id'];

$query = "SELECT 
            p.*,
            b.harga_per_jam
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          WHERE p.id_peminjaman = $id";

$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

if ($data) {
    echo json_encode([
        'success' => true,
        'id_peminjaman' => $data['id_peminjaman'],
        'id_user' => $data['id_user'],
        'id_barang' => $data['id_barang'],
        'tanggal_pinjam' => $data['tanggal_pinjam'],
        'jam_pinjam' => $data['jam_pinjam'],
        'durasi_jam' => $data['durasi_jam'],
        'status' => $data['status'],
        'harga_per_jam' => $data['harga_per_jam']
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>