<?php
session_start();
include "../partials/header.php";
include "../partials/navbar.php";
include "../../config/connection.php";

// Cek login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../auth/login';
    </script>";
    exit;
}

// Cek role harus peminjam
if ($_SESSION['role'] != 'peminjam') {
    echo "<script>
        alert('Halaman ini hanya untuk peminjam!');
        window.location.href = '/ukk-pinjaman/';
    </script>";
    exit;
}

$user_id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$nama_user = $_SESSION['nama'];

// Proses peminjaman lampu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pinjam_lampu'])) {
    $barang_id = (int)$_POST['barang_id'];
    $jumlah = (int)$_POST['jumlah'];
    $tgl_pinjam = mysqli_real_escape_string($connect, $_POST['tgl_pinjam']);
    $jam_pinjam = mysqli_real_escape_string($connect, $_POST['jam_pinjam']);
    $tgl_kembali = mysqli_real_escape_string($connect, $_POST['tgl_kembali']);
    $jam_kembali = mysqli_real_escape_string($connect, $_POST['jam_kembali']);
    $keterangan = mysqli_real_escape_string($connect, $_POST['keterangan']);

    // Cek stok lampu
    $cek_stok = mysqli_query($connect, "SELECT stok, harga_per_jam, nama_barang FROM barang WHERE id_barang = $barang_id");
    $lampu = mysqli_fetch_assoc($cek_stok);

    if (!$lampu) {
        echo "<script>alert('Lampu tidak ditemukan!');</script>";
    } elseif ($lampu['stok'] < $jumlah) {
        echo "<script>alert('Stok lampu tidak mencukupi! Tersedia: {$lampu['stok']} unit');</script>";
    } else {
        // Hitung durasi dalam jam
        $datetime_pinjam = strtotime("$tgl_pinjam $jam_pinjam");
        $datetime_kembali = strtotime("$tgl_kembali $jam_kembali");
        $durasi_jam = ceil(($datetime_kembali - $datetime_pinjam) / 3600);
        
        if ($durasi_jam <= 0) $durasi_jam = 1;
        
        $total_harga = $durasi_jam * $jumlah * $lampu['harga_per_jam'];

        // INSERT peminjaman
        $query = "INSERT INTO peminjaman (
            id_user, id_barang, jumlah, 
            tanggal_pinjam, jam_pinjam, tanggal_kembali, jam_kembali,
            durasi_jam, total_harga, keterangan_pengembalian, status, created_at
        ) VALUES (
            $user_id, $barang_id, $jumlah,
            '$tgl_pinjam', '$jam_pinjam', '$tgl_kembali', '$jam_kembali',
            $durasi_jam, $total_harga, '$keterangan', 'dipinjam', NOW()
        )";

        if (mysqli_query($connect, $query)) {
            // Kurangi stok
            $stok_baru = $lampu['stok'] - $jumlah;
            mysqli_query($connect, "UPDATE barang SET stok = $stok_baru WHERE id_barang = $barang_id");
            
            // Log aktivitas
            $aktivitas = "Melakukan peminjaman lampu: {$lampu['nama_barang']} ($jumlah unit)";
            mysqli_query($connect, "INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu) 
                VALUES ($user_id, '$username', 'peminjam', '$aktivitas', NOW())");
            
            echo "<script>
                alert('Peminjaman berhasil!');
                window.location.href = 'peminjaman';
            </script>";
        } else {
            echo "<script>alert('Gagal mengajukan peminjaman: " . addslashes(mysqli_error($connect)) . "');</script>";
        }
    }
}

// Pagination
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($connect, $_GET['status']) : '';

// Query search & filter
$where = "p.id_user = $user_id";
if (!empty($search)) {
    $where .= " AND (p.id_peminjaman LIKE '%$search%' OR b.nama_barang LIKE '%$search%')";
}
if (!empty($filter_status)) {
    $where .= " AND p.status = '$filter_status'";
}

// Total data
$query_total = mysqli_query($connect, "SELECT COUNT(*) as total 
    FROM peminjaman p 
    LEFT JOIN barang b ON p.id_barang = b.id_barang 
    WHERE $where");
$total_data = mysqli_fetch_assoc($query_total)['total'];
$total_pages = ceil($total_data / $limit);

// Query peminjaman
$query_peminjaman = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.harga_per_jam, b.foto 
    FROM peminjaman p 
    LEFT JOIN barang b ON p.id_barang = b.id_barang 
    WHERE $where
    ORDER BY p.id_peminjaman DESC 
    LIMIT $offset, $limit");

// Hitung statistik
$q_stats = mysqli_query($connect, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as aktif,
    SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as selesai,
    SUM(CASE WHEN status = 'batal' THEN 1 ELSE 0 END) as batal
    FROM peminjaman WHERE id_user = $user_id");
$stats = mysqli_fetch_assoc($q_stats);

// Ambil daftar lampu untuk dropdown
$query_lampu = mysqli_query($connect, "SELECT id_barang, nama_barang, stok, harga_per_jam, foto 
    FROM barang WHERE stok > 0 ORDER BY nama_barang");
?>

<!-- LOADING SIMPEL -->
<div id="loadingOverlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:linear-gradient(135deg,#6a1b9a,#9c27b0);display:flex;justify-content:center;align-items:center;z-index:9999;transition:opacity 0.3s;">
    <div style="text-align:center;">
        <div style="width:50px;height:50px;border:3px solid rgba(255,255,255,0.2);border-top:3px solid white;border-radius:50%;animation:spin 0.6s linear infinite;margin:0 auto 15px;"></div>
        <div style="color:white;font-size:1rem;font-weight:600;">Memuat Peminjaman Lampu...</div>
    </div>
</div>
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<script>
    setTimeout(function() {
        const loading = document.getElementById('loadingOverlay');
        if(loading) {
            loading.style.opacity = '0';
            setTimeout(() => loading.style.display = 'none', 300);
        }
    }, 700);
</script>

<!-- START SECTION TOP -->
<section class="section-top">
    <div class="container">
        <div class="col-lg-10 offset-lg-1 text-center">
            <div class="section-top-title">
                <h1><i class="fas fa-lightbulb me-3"></i>Peminjaman Saya</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li> / Peminjaman Saya</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- START PEMINJAMAN LIST -->
<section class="peminjaman_area section-padding">
    <div class="container">
        <div class="section-title text-center">
            <h2>Riwayat <span>Peminjaman</span> Lampu</h2>
            <p>Halo, <strong><?= htmlspecialchars($nama_user) ?></strong>! Berikut adalah daftar peminjaman lampu Anda</p>
        </div>

        <!-- Tombol Sewa Lampu Baru -->
        <div class="text-center mb-4">
            <button class="btn-rent-new" onclick="openRentModal()">
                <i class="fas fa-plus-circle"></i> Sewa Lampu Baru
            </button>
        </div>

        <!-- Statistik Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-list"></i></div>
                <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                <div class="stat-label">Total</div>
            </div>
            <div class="stat-card aktif">
                <div class="stat-icon"><i class="fas fa-lightbulb"></i></div>
                <div class="stat-number"><?= $stats['aktif'] ?? 0 ?></div>
                <div class="stat-label">Dipinjam</div>
            </div>
            <div class="stat-card selesai">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?= $stats['selesai'] ?? 0 ?></div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-card batal">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-number"><?= $stats['batal'] ?? 0 ?></div>
                <div class="stat-label">Batal</div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="filter-bar">
            <div class="show-entries">
                <label>Tampilkan:</label>
                <select id="limit-select" class="form-select-sm">
                    <option value="6" <?= $limit == 6 ? 'selected' : '' ?>>6</option>
                    <option value="12" <?= $limit == 12 ? 'selected' : '' ?>>12</option>
                    <option value="24" <?= $limit == 24 ? 'selected' : '' ?>>24</option>
                    <option value="48" <?= $limit == 48 ? 'selected' : '' ?>>48</option>
                </select>
                <span>data</span>
            </div>
            <div class="filter-status">
                <label>Filter Status:</label>
                <select id="status-filter" class="form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" <?= $filter_status == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                    <option value="dikembalikan" <?= $filter_status == 'dikembalikan' ? 'selected' : '' ?>>Selesai</option>
                    <option value="batal" <?= $filter_status == 'batal' ? 'selected' : '' ?>>Batal</option>
                </select>
            </div>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="Cari ID atau lampu..." value="<?= htmlspecialchars($search) ?>">
            </div>
        </div>

        <!-- Card Grid Peminjaman -->
        <div class="cards-grid">
            <?php if (mysqli_num_rows($query_peminjaman) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query_peminjaman)):
                    $status_class = '';
                    $status_text = '';
                    $status_icon = '';
                    switch ($row['status']) {
                        case 'dipinjam':
                            $status_class = 'status-dipinjam';
                            $status_text = 'Dipinjam';
                            $status_icon = 'fa-hourglass-half';
                            break;
                        case 'dikembalikan':
                            $status_class = 'status-dikembalikan';
                            $status_text = 'Selesai';
                            $status_icon = 'fa-check-circle';
                            break;
                        case 'batal':
                            $status_class = 'status-batal';
                            $status_text = 'Batal';
                            $status_icon = 'fa-times-circle';
                            break;
                        default:
                            $status_class = 'status-dipinjam';
                            $status_text = 'Dipinjam';
                            $status_icon = 'fa-hourglass-half';
                    }
                ?>
                    <div class="card-peminjaman">
                        <div class="card-header">
                            <div class="card-id">
                                <i class="fas fa-hashtag"></i>
                                <span>ID: <?= $row['id_peminjaman'] ?></span>
                            </div>
                            <div class="status-badge <?= $status_class ?>">
                                <i class="fas <?= $status_icon ?>"></i> <?= $status_text ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="lampu-info">
                                <div class="lampu-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="lampu-detail">
                                    <h4><?= htmlspecialchars($row['nama_barang']) ?></h4>
                                    <p class="harga">Rp <?= number_format($row['harga_per_jam'], 0, ',', '.') ?> / jam</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <label>Tanggal Pinjam</label>
                                        <span><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></span>
                                        <small><?= $row['jam_pinjam'] ?></small>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <div>
                                        <label>Tanggal Kembali</label>
                                        <span><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></span>
                                        <small><?= $row['jam_kembali'] ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <label>Durasi</label>
                                        <span><?= $row['durasi_jam'] ?> Jam</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-money-bill"></i>
                                    <div>
                                        <label>Total Harga</label>
                                        <span class="total">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="detail-peminjaman?id=<?= $row['id_peminjaman'] ?>" class="btn-detail">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Belum Ada Peminjaman</h4>
                    <p>Anda belum melakukan peminjaman lampu. Klik tombol "Sewa Lampu Baru" untuk memulai.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-wrapper">
                <div class="data-info">
                    Menampilkan <?= min($offset + 1, $total_data) ?> - <?= min($offset + $limit, $total_data) ?> dari <?= $total_data ?> peminjaman
                </div>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link">&laquo; Sebelumnya</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i >= $page - 2 && $i <= $page + 2): ?>
                            <a href="?page=<?= $i ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&status=<?= urlencode($filter_status) ?>&search=<?= urlencode($search) ?>" class="page-link">Selanjutnya &raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- MODAL SEWA LAMPU -->
<div id="rentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-lightbulb"></i> Form Peminjaman Lampu</h3>
            <span class="close" onclick="closeRentModal()">&times;</span>
        </div>
        <form method="POST" action="" id="rentForm">
            <div class="modal-body">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nama Peminjam</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($nama_user) ?>" disabled>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lightbulb"></i> Pilih Lampu *</label>
                    <select name="barang_id" id="lampu_select" class="form-control" required onchange="updateLampuInfo()">
                        <option value="">-- Pilih Lampu --</option>
                        <?php 
                        mysqli_data_seek($query_lampu, 0);
                        while ($lampu = mysqli_fetch_assoc($query_lampu)): 
                        ?>
                            <option value="<?= $lampu['id_barang'] ?>"
                                data-nama="<?= htmlspecialchars($lampu['nama_barang']) ?>"
                                data-harga="<?= $lampu['harga_per_jam'] ?>"
                                data-stok="<?= $lampu['stok'] ?>">
                                <?= htmlspecialchars($lampu['nama_barang']) ?> - Stok: <?= $lampu['stok'] ?> - Rp <?= number_format($lampu['harga_per_jam'], 0, ',', '.') ?>/jam
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div id="lampu_info" class="motor-info-preview" style="display: none;">
                    <div class="info-preview">
                        <span id="lampu_nama"></span>
                        <span id="lampu_harga"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label><i class="fas fa-calendar"></i> Tanggal Pinjam *</label>
                        <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required>
                    </div>
                    <div class="form-group half">
                        <label><i class="fas fa-clock"></i> Jam Pinjam *</label>
                        <input type="time" name="jam_pinjam" id="jam_pinjam" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label><i class="fas fa-calendar-check"></i> Tanggal Kembali *</label>
                        <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" required>
                    </div>
                    <div class="form-group half">
                        <label><i class="fas fa-clock"></i> Jam Kembali *</label>
                        <input type="time" name="jam_kembali" id="jam_kembali" class="form-control" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label><i class="fas fa-boxes"></i> Jumlah *</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group half">
                        <label><i class="fas fa-money-bill"></i> Total Harga</label>
                        <div class="total-price" id="total_price">Rp 0</div>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRentModal()">Batal</button>
                <button type="submit" name="pinjam_lampu" class="btn-submit">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Tombol Sewa Baru */
    .btn-rent-new {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .btn-rent-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
    }

    /* Section Top */
    .section-top {
        background: linear-gradient(135deg, #6a1b9a, #9c27b0);
        padding: 60px 0;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .section-top::before {
        content: "💡";
        font-size: 200px;
        position: absolute;
        bottom: -50px;
        right: -50px;
        opacity: 0.1;
    }

    .section-top h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .section-top ul {
        list-style: none;
        display: flex;
        justify-content: center;
        gap: 10px;
        padding: 0;
    }

    .section-top ul li a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }

    /* Peminjaman Area */
    .peminjaman_area {
        padding: 60px 0;
        background: #f8fafc;
    }

    .section-title {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-title h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a2e;
    }

    .section-title h2 span {
        color: #6a1b9a;
    }

    /* Stats Row */
    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px 30px;
        text-align: center;
        min-width: 140px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
    }

    .stat-icon i {
        font-size: 28px;
        color: white;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3748;
    }

    .stat-label {
        font-size: 0.7rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-card.aktif .stat-icon {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .stat-card.selesai .stat-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .stat-card.batal .stat-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    /* Filter Bar */
    .filter-bar {
        background: white;
        border-radius: 20px;
        padding: 15px 25px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .show-entries, .filter-status {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }

    .show-entries label, .filter-status label {
        font-weight: 600;
        color: #4a5568;
    }

    .form-select-sm {
        padding: 8px 12px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        font-size: 0.85rem;
    }

    .search-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 16px;
        transition: all 0.3s;
    }

    .search-box:focus-within {
        border-color: #6a1b9a;
        box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.1);
    }

    .search-box i {
        color: #6a1b9a;
    }

    .search-box input {
        border: none;
        background: transparent;
        padding: 6px 10px;
        width: 250px;
        outline: none;
    }

    /* Card Grid */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .card-peminjaman {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .card-peminjaman:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(106, 27, 154, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-id {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-dipinjam {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .status-dikembalikan {
        background: #d1fae5;
        color: #065f46;
    }

    .status-batal {
        background: #fee2e2;
        color: #991b1b;
    }

    .card-body {
        padding: 20px;
    }

    .lampu-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f2f5;
    }

    .lampu-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #e0aaff, #9c27b0);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .lampu-icon i {
        color: white;
        font-size: 24px;
    }

    .lampu-detail h4 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #1a1a2e;
    }

    .lampu-detail .harga {
        font-size: 0.8rem;
        color: #6a1b9a;
        font-weight: 600;
        margin: 0;
    }

    .info-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .info-item {
        flex: 1;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 0.8rem;
    }

    .info-item i {
        color: #6a1b9a;
        margin-top: 2px;
        font-size: 14px;
    }

    .info-item label {
        display: block;
        font-size: 0.65rem;
        color: #718096;
        margin-bottom: 2px;
    }

    .info-item span {
        display: block;
        font-weight: 600;
        color: #2d3748;
    }

    .info-item small {
        font-size: 0.7rem;
        color: #718096;
    }

    .info-item .total {
        color: #6a1b9a;
        font-size: 0.9rem;
    }

    .card-footer {
        padding: 15px 20px;
        background: #f8fafc;
        border-top: 1px solid #f0f2f5;
    }

    .btn-detail {
        width: 100%;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .data-info {
        font-size: 0.8rem;
        color: #718096;
    }

    .pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 8px 14px;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: #4a5568;
        transition: all 0.2s;
    }

    .page-link:hover,
    .page-link.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 20px;
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    .empty-state h4 {
        font-size: 1.3rem;
        margin-bottom: 10px;
        color: #4a5568;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        margin: 5% auto;
        width: 550px;
        max-width: 90%;
        border-radius: 24px;
        animation: slideDown 0.3s ease;
        overflow: hidden;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }

    .close {
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        color: white;
        transition: all 0.2s;
    }

    .close:hover {
        opacity: 0.7;
    }

    .modal-body {
        padding: 25px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .form-group.half {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        color: #4a5568;
    }

    .form-group label i {
        margin-right: 8px;
        color: #667eea;
    }

    .form-control, .modal select {
        width: 100%;
        padding: 12px 15px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .form-control:focus, .modal select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .motor-info-preview {
        margin-bottom: 18px;
    }

    .info-preview {
        background: linear-gradient(135deg, #f0fff4, #e6fffa);
        padding: 12px 15px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
    }

    .total-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #667eea;
        background: #f8fafc;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 25px;
        border-top: 1px solid #eef2f6;
        background: #f8fafc;
    }

    .btn-cancel {
        background: #f1f5f9;
        border: none;
        color: #4a5568;
        padding: 10px 24px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        padding: 10px 28px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .section-top h1 {
            font-size: 1.8rem;
        }
        
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        .stats-row {
            gap: 12px;
        }

        .stat-card {
            min-width: calc(33% - 12px);
            padding: 15px;
        }

        .stat-number {
            font-size: 1.3rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            text-align: center;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .modal-content {
            margin: 20% auto;
        }

        .cards-grid {
            grid-template-columns: 1fr;
        }

        .info-row {
            flex-direction: column;
            gap: 10px;
        }
    }
</style>

<script>
    // Modal functions
    function openRentModal() {
        document.getElementById('rentModal').style.display = 'block';
        setMinDates();
    }

    function closeRentModal() {
        document.getElementById('rentModal').style.display = 'none';
    }

    // Set minimal tanggal
    function setMinDates() {
        const today = new Date().toISOString().split('T')[0];
        const now = new Date();
        const currentTime = now.toTimeString().slice(0, 5);
        
        document.getElementById('tgl_pinjam').min = today;
        document.getElementById('tgl_kembali').min = today;
        document.getElementById('jam_pinjam').value = currentTime;
        
        const nextHour = new Date(now.getTime() + 60 * 60 * 1000);
        document.getElementById('jam_kembali').value = nextHour.toTimeString().slice(0, 5);
    }

    // Update lampu info
    function updateLampuInfo() {
        const select = document.getElementById('lampu_select');
        const selectedOption = select.options[select.selectedIndex];
        const lampuInfo = document.getElementById('lampu_info');

        if (select.value) {
            const nama = selectedOption.getAttribute('data-nama');
            const harga = parseInt(selectedOption.getAttribute('data-harga'));
            const stok = parseInt(selectedOption.getAttribute('data-stok'));

            document.getElementById('lampu_nama').innerHTML = `<strong>${nama}</strong>`;
            document.getElementById('lampu_harga').innerHTML = `Rp ${harga.toLocaleString('id-ID')}/jam`;
            document.getElementById('jumlah').max = stok;
            lampuInfo.style.display = 'block';
        } else {
            lampuInfo.style.display = 'none';
        }
        hitungTotal();
    }

    // Hitung total harga
    function hitungTotal() {
        const select = document.getElementById('lampu_select');
        const tglPinjam = document.getElementById('tgl_pinjam').value;
        const jamPinjam = document.getElementById('jam_pinjam').value;
        const tglKembali = document.getElementById('tgl_kembali').value;
        const jamKembali = document.getElementById('jam_kembali').value;
        const jumlah = parseInt(document.getElementById('jumlah').value) || 1;

        if (select.value && tglPinjam && jamPinjam && tglKembali && jamKembali) {
            const datetimePinjam = new Date(`${tglPinjam}T${jamPinjam}`);
            const datetimeKembali = new Date(`${tglKembali}T${jamKembali}`);
            
            if (datetimeKembali > datetimePinjam) {
                const harga = parseInt(select.options[select.selectedIndex].getAttribute('data-harga'));
                const diffMs = datetimeKembali - datetimePinjam;
                const durasiJam = Math.ceil(diffMs / (1000 * 60 * 60));
                const total = durasiJam * jumlah * harga;
                document.getElementById('total_price').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                document.getElementById('total_price').innerHTML = 'Rp 0';
            }
        } else {
            document.getElementById('total_price').innerHTML = 'Rp 0';
        }
    }

    // Event listeners
    document.getElementById('lampu_select')?.addEventListener('change', hitungTotal);
    document.getElementById('tgl_pinjam')?.addEventListener('change', hitungTotal);
    document.getElementById('jam_pinjam')?.addEventListener('change', hitungTotal);
    document.getElementById('tgl_kembali')?.addEventListener('change', hitungTotal);
    document.getElementById('jam_kembali')?.addEventListener('change', hitungTotal);
    document.getElementById('jumlah')?.addEventListener('input', hitungTotal);

    // Filter and search
    document.getElementById('limit-select')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', this.value);
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    document.getElementById('status-filter')?.addEventListener('change', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (this.value) {
            urlParams.set('status', this.value);
        } else {
            urlParams.delete('status');
        }
        urlParams.set('page', 1);
        window.location.href = '?' + urlParams.toString();
    });

    let searchTimeout;
    document.getElementById('search-input')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            if (this.value) {
                urlParams.set('search', this.value);
            } else {
                urlParams.delete('search');
            }
            urlParams.set('page', 1);
            window.location.href = '?' + urlParams.toString();
        }, 500);
    });

    // Tutup modal klik di luar
    window.onclick = function(event) {
        const modal = document.getElementById('rentModal');
        if (event.target == modal) {
            closeRentModal();
        }
    }
</script>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>