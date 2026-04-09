<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../app.php';

// Query semua peminjaman - tanpa JOIN ke users
$qPeminjaman = "SELECT 
                    p.*,
                    b.nama_barang,
                    b.harga_per_jam
                FROM peminjaman p
                LEFT JOIN barang b ON p.id_barang = b.id_barang
                ORDER BY p.id_peminjaman DESC";
$result = mysqli_query($connect, $qPeminjaman);

$peminjaman = [];
while ($row = mysqli_fetch_assoc($result)) {
    $peminjaman[] = $row;
}
$totalPeminjaman = count($peminjaman);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman - Admin Panel</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        body { background-color: #f8f9fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        #main {
            margin-left: 260px;
            margin-top: 70px;
            padding: 25px;
            width: calc(100% - 260px);
            min-height: calc(100vh - 70px);
            background-color: #f8f9fc;
        }
        @media (max-width: 768px) {
            #main { margin-left: 0; width: 100%; padding: 15px; }
        }
        .main-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e3e6f0;
            overflow: hidden;
        }
        .card-header-custom {
            background: #ffffff;
            border-bottom: 2px solid #f0f2f5;
            padding: 20px 30px;
        }
        .card-body-custom { padding: 30px; }
        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4e73df;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        .btn-add:hover { transform: translateY(-3px); color: white; }
        .badge-dipinjam { background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%); color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
        .badge-dikembalikan { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
        .badge-batal { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); color: white; padding: 8px 20px; border-radius: 20px; font-weight: 600; font-size: 0.85rem; }
        .btn-action {
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 2px;
            text-decoration: none;
        }
        .btn-view { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
        .btn-edit { background: linear-gradient(135deg, #fad961 0%, #f76b1c 100%); color: white; }
        .btn-delete { background: linear-gradient(135deg, #ff5858 0%, #f09819 100%); color: white; }
        .btn-print { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); color: white; }
        .dataTable thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            text-align: center;
        }
        .dataTable tbody td { padding: 12px; vertical-align: middle; text-align: center; }
        .alert-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            border-radius: 10px;
        }
        @media print {
            .no-print, .sidebar, .navbar, .btn, .dataTables_filter, .dataTables_length, .dataTables_paginate, .footer-custom, .alert, .btn-add {
                display: none !important;
            }
            #main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 0 !important;
            }
            .card, .main-card {
                box-shadow: none !important;
                border: none !important;
            }
            body {
                background: white !important;
            }
            .struk-container {
                padding: 20px;
            }
        }
        .struk-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            font-family: 'Courier New', monospace;
        }
        .struk-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .struk-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .struk-line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .struk-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .struk-total {
            font-weight: bold;
            font-size: 16px;
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        .struk-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 12px;
        }
        .modal-struk {
            max-width: 500px;
        }
    </style>
</head>
<body>

<?php 
include '../../partials/header.php'; 
$page = 'peminjaman'; 
include '../../partials/sidebar.php'; 
?>

<div class="container-fluid">
    <div id="main">
        <div class="main-card">
            <div class="card-header-custom d-flex align-items-center justify-content-between">
                <h2 class="page-title">
                    <i class="fas fa-hand-holding"></i>
                    Data Peminjaman
                </h2>
                <a href="./create.php" class="btn btn-add">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Peminjaman
                </a>
            </div>
            
            <div class="card-body-custom">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Total <?php echo $totalPeminjaman; ?> Peminjaman</strong>
                    </div>
                </div>
                
                <?php if ($totalPeminjaman > 0): ?>
                    <div class="table-responsive">
                        <table id="peminjamanTable" class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Peminjaman</th>
                                    <th>ID User</th>
                                    <th>ID Barang</th>
                                    <th>Barang</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jam Pinjam</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peminjaman as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>#<?= $item['id_peminjaman'] ?></td>
                                    <td><?= $item['id_user'] ?></td>
                                    <td><?= $item['id_barang'] ?></td>
                                    <td><?= htmlspecialchars($item['nama_barang'] ?? '-') ?></td>
                                    <td><?= date('d-m-Y', strtotime($item['tanggal_pinjam'])) ?></td>
                                    <td><?= $item['jam_pinjam'] ?></td>
                                    <td><?= $item['durasi_jam'] ?> jam</td>
                                    <td>Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge-<?= $item['status'] ?>">
                                            <?php 
                                                $status_text = [
                                                    'dipinjam' => 'Dipinjam',
                                                    'dikembalikan' => 'Dikembalikan',
                                                    'batal' => 'Batal'
                                                ];
                                                echo $status_text[$item['status']] ?? $item['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="./show.php?id=<?= $item['id_peminjaman'] ?>" class="btn-action btn-view" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="./edit.php?id=<?= $item['id_peminjaman'] ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="printStruk(<?= $item['id_peminjaman'] ?>)" class="btn-action btn-print" title="Cetak Struk">
                                            <i class="fas fa-print"></i>
                                        </button>
                                        <a href="../../action/peminjaman/destroy.php?id=<?= $item['id_peminjaman'] ?>" 
                                           onclick="return confirm('Yakin hapus peminjaman ini?')"
                                           class="btn-action btn-delete" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-hand-holding fa-4x text-muted mb-3"></i>
                        <h5>Belum Ada Data Peminjaman</h5>
                        <p>Mulai dengan menambahkan peminjaman baru</p>
                    </div>
                <?php endif; ?>
                
                <div class="footer-custom">
                    <p>&copy; <?= date('Y') ?> Website Peminjaman Alat Berat.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Struk -->
<div class="modal fade" id="strukModal" tabindex="-1">
    <div class="modal-dialog modal-struk">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Struk Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="strukContent">
                <!-- Konten struk akan diisi via JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/script.php'; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#peminjamanTable').DataTable({
        language: {
            processing: "Memproses...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: { previous: "<", next: ">" }
        },
        pageLength: 10,
        order: [[1, 'desc']]
    });
});

function printStruk(id) {
    $.ajax({
        url: '../../action/peminjaman/print_struk.php',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                let strukHtml = `
                    <div class="struk-container">
                        <div class="struk-header">
                            <div class="struk-title">PEMINJAMAN ALAT BERAT</div>
                            <div>Jl. Contoh No. 123, Kota</div>
                            <div>Telp: (021) 1234567</div>
                            <div class="struk-line"></div>
                            <div>No. Transaksi: #${data.id_peminjaman}</div>
                            <div>Tanggal: ${data.tanggal_transaksi}</div>
                        </div>
                        
                        <div class="struk-row">
                            <span>ID User:</span>
                            <span><strong>${data.id_user}</strong></span>
                        </div>
                        <div class="struk-row">
                            <span>ID Barang:</span>
                            <span><strong>${data.id_barang}</strong></span>
                        </div>
                        <div class="struk-row">
                            <span>Barang:</span>
                            <span><strong>${data.nama_barang}</strong></span>
                        </div>
                        <div class="struk-line"></div>
                        
                        <div class="struk-row">
                            <span>Tanggal Pinjam:</span>
                            <span>${data.tanggal_pinjam}</span>
                        </div>
                        <div class="struk-row">
                            <span>Jam Pinjam:</span>
                            <span>${data.jam_pinjam}</span>
                        </div>
                        <div class="struk-row">
                            <span>Tanggal Kembali:</span>
                            <span>${data.tanggal_kembali}</span>
                        </div>
                        <div class="struk-row">
                            <span>Jam Kembali:</span>
                            <span>${data.jam_kembali}</span>
                        </div>
                        <div class="struk-row">
                            <span>Durasi Sewa:</span>
                            <span>${data.durasi_jam} Jam</span>
                        </div>
                        <div class="struk-line"></div>
                        
                        <div class="struk-row">
                            <span>Harga per Jam:</span>
                            <span>Rp ${data.harga_per_jam}</span>
                        </div>
                        <div class="struk-row struk-total">
                            <span>TOTAL HARGA:</span>
                            <span>Rp ${data.total_harga}</span>
                        </div>
                        <div class="struk-line"></div>
                        
                        <div class="struk-row">
                            <span>Status:</span>
                            <span><strong>${data.status}</strong></span>
                        </div>
                        
                        <div class="struk-footer">
                            <div>Terima kasih atas kepercayaan Anda</div>
                            <div>Barang wajib dikembalikan tepat waktu</div>
                            <div>--- Simpan struk ini sebagai bukti ---</div>
                        </div>
                    </div>
                `;
                $('#strukContent').html(strukHtml);
                $('#strukModal').modal('show');
            } else {
                alert(data.message);
            }
        },
        error: function() {
            alert('Gagal mengambil data struk');
        }
    });
}
</script>

</body>
</html>