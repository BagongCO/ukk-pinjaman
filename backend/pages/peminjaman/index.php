<?php
// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan session_start() di awal
session_start();

// Include koneksi database
include '../../app.php';

// Query semua peminjaman
$qPeminjaman = "SELECT 
                    p.*,
                    b.nama_barang
                FROM peminjaman p
                LEFT JOIN barang b ON p.id_barang = b.id_barang
                ORDER BY p.id_peminjaman DESC";
$result = mysqli_query($connect, $qPeminjaman);

if (!$result) {
    die("Query error: " . mysqli_error($connect));
}

// Simpan data ke array
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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        /* ========== RESET & LAYOUT UTAMA ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fc;
            overflow-x: hidden;
        }

        /* ========== SIDEBAR STYLE ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar .sidebar-brand {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .sidebar-brand i {
            margin-right: 10px;
        }

        .sidebar .nav-item {
            padding: 5px 20px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* ========== NAVBAR STYLE ========== */
        .topbar {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .topbar .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #4e73df;
            display: none;
        }

        .topbar .navbar-brand {
            font-weight: bold;
            color: #4e73df;
            text-decoration: none;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
            transition: all 0.3s;
        }

        /* ========== CARD & TABLE STYLE ========== */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.35rem;
        }

        .badge-dipinjam {
            background: #fff3e0;
            color: #f6c23e;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-dikembalikan {
            background: #e3fcec;
            color: #1cc88a;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-batal {
            background: #fce3e0;
            color: #e74a3b;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .btn-action {
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 2px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-view {
            background: #e3f2fd;
            color: #2196f3;
        }
        .btn-edit {
            background: #fff3e0;
            color: #f6c23e;
        }
        .btn-delete {
            background: #fce3e0;
            color: #e74a3b;
        }
        .btn-print {
            background: #e8eaf6;
            color: #5c6bc0;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            transition: 0.2s;
        }
        .dataTable thead th {
            background: #4e73df;
            color: white;
            padding: 12px;
            text-align: center;
        }
        .dataTable tbody td {
            padding: 10px;
            vertical-align: middle;
            text-align: center;
        }

        /* ========== STRUK PRINT STYLE ========== */
        @media print {
            .no-print, .sidebar, .topbar, .btn, .dataTables_filter, .dataTables_length, .dataTables_paginate, .footer-custom, .alert, .btn-add {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                margin-top: 0 !important;
                padding: 0 !important;
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

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .topbar {
                left: 0;
            }
            .topbar .toggle-sidebar {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-laptop-code"></i>
        <span>Admin Panel</span>
    </div>
    
    <div class="nav flex-column mt-3">
        <div class="nav-item">
            <a class="nav-link" href="../dashboard/index.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="../akun/index.php">
                <i class="fas fa-users"></i>
                <span>Akun Operator</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="../log/index.php">
                <i class="fas fa-history"></i>
                <span>Log Aktivitas</span>
            </a>
        </div>
        
        <div class="nav-item mt-3">
            <span style="color: rgba(255,255,255,0.5); font-size: 0.7rem; padding-left: 15px;">TRANSAKSI</span>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="../barang/index.php">
                <i class="fas fa-boxes"></i>
                <span>Data Barang</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link active" href="index.php">
                <i class="fas fa-hand-holding"></i>
                <span>Peminjaman</span>
            </a>
        </div>
        <div class="nav-item">
            <a class="nav-link" href="../laporan/index.php">
                <i class="fas fa-file-alt"></i>
                <span>Laporan Peminjaman</span>
            </a>
        </div>
        
        <div class="nav-item mt-3">
            <a class="nav-link" href="../../action/auth/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<!-- TOPBAR / NAVBAR -->
<div class="topbar">
    <button class="toggle-sidebar" id="toggleSidebarBtn">
        <i class="fas fa-bars"></i>
    </button>
    <a href="../dashboard/index.php" class="navbar-brand">
        <i class="fas fa-tools"></i> Rental Alat Berat
    </a>
    <div class="user-info">
        <span class="text-muted">
            <i class="fas fa-user-circle"></i> 
            <?= $_SESSION['username'] ?? 'Admin' ?>
        </span>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main-content" id="mainContent">
    <div class="container-fluid px-0">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-hand-holding"></i> Data Peminjaman
                </h6>
                <a href="./create.php" class="btn btn-primary btn-sm mt-2 mt-sm-0">
                    <i class="fas fa-plus"></i> Tambah Peminjaman
                </a>
            </div>
            <div class="card-body">
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
                
                <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <i class="fas fa-info-circle"></i>
                        <strong>Total <?php echo $totalPeminjaman; ?> Peminjaman</strong>
                    </div>
                </div>
                
                <?php if ($totalPeminjaman > 0): ?>
                    <div class="table-responsive">
                        <table id="peminjamanTable" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Peminjaman</th>
                                    <th>ID User</th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jam Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Jam Kembali</th>
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
                                    <td><?= $item['id_peminjaman'] ?></td>
                                    <td><?= $item['id_user'] ?></td>
                                    <td><?= $item['id_barang'] ?></td>
                                    <td><?= htmlspecialchars($item['nama_barang'] ?? '-') ?></td>
                                    <td><?= date('d-m-Y', strtotime($item['tanggal_pinjam'])) ?></td>
                                    <td><?= $item['jam_pinjam'] ?></td>
                                    <td><?= $item['tanggal_kembali'] ? date('d-m-Y', strtotime($item['tanggal_kembali'])) : '-' ?></td>
                                    <td><?= $item['jam_kembali'] ?? '-' ?></td>
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
                        <a href="./create.php" class="btn btn-primary">Tambah Peminjaman</a>
                    </div>
                <?php endif; ?>
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

<!-- jQuery -->
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
            paginate: {
                previous: "<",
                next: ">"
            }
        },
        pageLength: 10,
        order: [[1, 'desc']]
    });
    
    // Toggle sidebar untuk mobile
    $('#toggleSidebarBtn').click(function() {
        $('#sidebar').toggleClass('show');
    });
    
    // Tutup sidebar saat klik di luar (mobile)
    $(document).click(function(event) {
        if (!$(event.target).closest('#sidebar').length && !$(event.target).closest('.toggle-sidebar').length) {
            if ($(window).width() <= 768) {
                $('#sidebar').removeClass('show');
            }
        }
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