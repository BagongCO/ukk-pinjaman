<?php
session_start();
include '../../app.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

$id = (int)$_GET['id'];

$query = "SELECT 
            p.*,
            b.nama_barang,
            b.harga_per_jam,
            u.username
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          LEFT JOIN users u ON p.id_user = u.id_user
          WHERE p.id_peminjaman = $id";

$result = mysqli_query($connect, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

// Format data untuk struk
$response = [
    'success' => true,
    'id_peminjaman' => $data['id_peminjaman'],
    'id_user' => $data['id_user'],
    'username' => $data['username'] ?? 'User #'.$data['id_user'],
    'id_barang' => $data['id_barang'],
    'nama_barang' => $data['nama_barang'] ?? '-',
    'tanggal_pinjam' => date('d-m-Y', strtotime($data['tanggal_pinjam'])),
    'jam_pinjam' => $data['jam_pinjam'],
    'tanggal_kembali' => $data['tanggal_kembali'] ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : '-',
    'jam_kembali' => $data['jam_kembali'] ?? '-',
    'durasi_jam' => $data['durasi_jam'],
    'harga_per_jam' => number_format($data['harga_per_jam'], 0, ',', '.'),
    'total_harga' => number_format($data['total_harga'], 0, ',', '.'),
    'status' => $data['status'],
    'denda' => isset($data['denda']) ? number_format($data['denda'], 0, ',', '.') : '0',
    'tanggal_transaksi' => date('d-m-Y H:i:s', strtotime($data['created_at']))
];

echo json_encode($response);
?>