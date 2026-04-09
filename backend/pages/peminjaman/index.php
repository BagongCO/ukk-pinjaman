<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../app.php';

// Query semua peminjaman
$query = "SELECT 
            p.*,
            b.nama_barang
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          ORDER BY p.id_peminjaman DESC";
$result = mysqli_query($connect, $query);

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
    <title>Data Peminjaman</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        /* Sidebar style */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }

        .sidebar .logo h3 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 25px;
            margin: 5px 0;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            border-left: 4px solid white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 25px;
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Navbar */
        .navbar-top {
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            color: #666;
        }

        /* Card */
        .card-custom {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 20px 25px;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }

        .card-header-custom h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Table */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom thead tr {
            background: #f8f9fc;
            border-bottom: 2px solid #eef2f7;
        }

        .table-custom thead th {
            padding: 15px 20px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-custom tbody tr {
            border-bottom: 1px solid #eef2f7;
            transition: all 0.2s;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fc;
        }

        .table-custom tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            color: #4a5568;
        }

        /* Badge status */
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-dipinjam {
            background: #fff3e0;
            color: #f6c23e;
        }

        .badge-dikembalikan {
            background: #e3fcec;
            color: #1cc88a;
        }

        .badge-batal {
            background: #fce3e0;
            color: #e74a3b;
        }

        /* Action buttons */
        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 0 3px;
            transition: all 0.2s;
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
        }

        /* Footer */
        .footer-custom {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 0.8rem;
            border-top: 1px solid #eef2f7;
            margin-top: 20px;
        }

        /* Alert */
        .alert-custom {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #e3fcec;
            color: #1cc88a;
            border-left: 4px solid #1cc88a;
        }

        .alert-danger {
            background: #fce3e0;
            color: #e74a3b;
            border-left: 4px solid #e74a3b;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .table-custom thead th,
            .table-custom tbody td {
                padding: 10px 12px;
                font-size: 0.75rem;
            }
            .btn-action span {
                display: none;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <h3><i class="fas fa-lamp"></i> Peminjaman</h3>
        <p style="font-size: 12px; opacity: 0.7;">Alat Berat</p>
    </div>
    <div class="nav flex-column">
        <a href="#" class="nav-link active">
            <i class="fas fa-hand-holding"></i> Peminjaman
        </a>
        <a href="../barang/index.php" class="nav-link">
            <i class="fas fa-box"></i> Barang
        </a>
        <a href="../akun_operator/index.php" class="nav-link">
            <i class="fas fa-users"></i> Akun Operator
        </a>
        <a href="../laporan/index.php" class="nav-link">
            <i class="fas fa-chart-line"></i> Laporan
        </a>
        <a href="../../auth/logout.php" class="nav-link" style="margin-top: 50px;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <!-- Navbar Top -->
    <div class="navbar-top">
        <div class="page-title">
            <i class="fas fa-hand-holding"></i> Data Peminjaman
        </div>
        <div class="user-info">
            <span><i class="fas fa-user"></i> <?php echo $_SESSION['username'] ?? 'Admin'; ?></span>
        </div>
    </div>

    <!-- Card -->
    <div class="card-custom">
        <div class="card-header-custom">
            <h5><i class="fas fa-list"></i> Daftar Peminjaman</h5>
            <a href="./create.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Peminjaman
            </a>
        </div>
        
        <div style="padding: 20px 25px;">
            <!-- Alert Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert-custom alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-custom alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Table -->
            <div style="overflow-x: auto;">
                <table class="table-custom" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Peminjaman</th>
                            <th>ID User</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Tgl Pinjam</th>
                            <th>Jam Pinjam</th>
                            <th>Durasi</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($totalPeminjaman > 0): ?>
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
                                    <span class="badge-status badge-<?= $item['status'] ?>">
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
                                        <i class="fas fa-eye"></i> <span>Detail</span>
                                    </a>
                                    <a href="./edit.php?id=<?= $item['id_peminjaman'] ?>" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i> <span>Edit</span>
                                    </a>
                                    <button onclick="printStruk(<?= $item['id_peminjaman'] ?>)" class="btn-action btn-print" title="Cetak Struk">
                                        <i class="fas fa-print"></i> <span>Struk</span>
                                    </button>
                                    <a href="../../action/peminjaman/destroy.php?id=<?= $item['id_peminjaman'] ?>" 
                                       onclick="return confirm('Yakin hapus peminjaman ini?')"
                                       class="btn-action btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i> <span>Hapus</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" style="text-align: center; padding: 50px;">
                                    <div class="empty-state">
                                        <i class="fas fa-hand-holding"></i>
                                        <h5>Belum Ada Data Peminjaman</h5>
                                        <p>Silakan tambah peminjaman baru</p>
                                        <a href="./create.php" class="btn-add" style="display: inline-flex; margin-top: 15px;">
                                            <i class="fas fa-plus"></i> Tambah Peminjaman
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="footer-custom">
                <p>&copy; <?= date('Y') ?> Sistem Peminjaman Alat Berat. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Struk -->
<div class="modal fade" id="strukModal" tabindex="-1">
    <div class="modal-dialog" style="max-width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Struk Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="strukContent">
                <!-- Konten struk -->
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function printStruk(id) {
    $.ajax({
        url: '../../action/peminjaman/print_struk.php',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                let strukHtml = `
                    <div style="font-family: 'Courier New', monospace; padding: 10px;">
                        <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 15px;">
                            <div style="font-size: 18px; font-weight: bold;">PEMINJAMAN ALAT</div>
                            <div style="font-size: 12px;">Jl. Contoh No. 123, Kota</div>
                            <div style="font-size: 12px;">Telp: (021) 1234567</div>
                        </div>
                        
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>No. Transaksi:</span>
                                <span><strong>#${data.id_peminjaman}</strong></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Tanggal:</span>
                                <span>${data.tanggal_transaksi}</span>
                            </div>
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>ID User:</span>
                                <span><strong>${data.id_user}</strong></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>ID Barang:</span>
                                <span><strong>${data.id_barang}</strong></span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Barang:</span>
                                <span><strong>${data.nama_barang}</strong></span>
                            </div>
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Tgl Pinjam:</span>
                                <span>${data.tanggal_pinjam}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Jam Pinjam:</span>
                                <span>${data.jam_pinjam}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Tgl Kembali:</span>
                                <span>${data.tanggal_kembali}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Jam Kembali:</span>
                                <span>${data.jam_kembali}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Durasi:</span>
                                <span>${data.durasi_jam} Jam</span>
                            </div>
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Harga/Jam:</span>
                                <span>Rp ${data.harga_per_jam}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: bold; border-top: 1px solid #000; padding-top: 10px; margin-top: 5px;">
                                <span>TOTAL:</span>
                                <span>Rp ${data.total_harga}</span>
                            </div>
                        </div>
                        
                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span>Status:</span>
                                <span><strong>${data.status}</strong></span>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px dashed #000; font-size: 11px;">
                            <div>Terima kasih atas kepercayaan Anda</div>
                            <div>Barang wajib dikembalikan tepat waktu</div>
                            <div>--- Simpan struk ini sebagai bukti ---</div>
                        </div>
                    </div>
                `;
                $('#strukContent').html(strukHtml);
                new bootstrap.Modal(document.getElementById('strukModal')).show();
            } else {
                alert(data.message);
            }
        },
        error: function() {
            alert('Gagal mengambil data struk');
        }
    });
}

// Mobile sidebar toggle
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
}
</script>

</body>
</html>