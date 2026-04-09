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
            b.nama_barang
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
        'nama_barang' => $data['nama_barang'],
        'tanggal_pinjam' => date('d-m-Y', strtotime($data['tanggal_pinjam'])),
        'jam_pinjam' => $data['jam_pinjam'],
        'tanggal_kembali' => $data['tanggal_kembali'] ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : '-',
        'jam_kembali' => $data['jam_kembali'] ?? '-',
        'durasi_jam' => $data['durasi_jam'],
        'total_harga' => number_format($data['total_harga'], 0, ',', '.'),
        'status' => $data['status'],
        'created_at' => date('d-m-Y H:i:s', strtotime($data['created_at']))
    ]);
} else {
    echo json_encode(['success' => false]);
}
?>