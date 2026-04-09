<?php
include '../../app.php';
include '../../partials/header.php';

// ====================== AMBIL DATA STATISTIK ======================

// Total Barang
$total_barang = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM barang"))['total'];

// Total Peminjaman (dari tabel peminjaman)
$total_peminjaman = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman"))['total'];

// Total Dipinjam (status dipinjam)
$total_dipinjam = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'dipinjam'"))['total'];

// Total Dikembalikan (status dikembalikan)
$total_kembali = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'dikembalikan'"))['total'];

// Total Batal
$total_batal = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'batal'"))['total'];

// Total Pendapatan (dari peminjaman yang sudah dikembalikan)
$pendapatan = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(total_harga) AS total FROM peminjaman WHERE status = 'dikembalikan'"))['total'];
$pendapatan = $pendapatan ?? 0;

// Total Denda (dari peminjaman yang sudah dikembalikan)
$total_denda = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(denda) AS total FROM peminjaman WHERE status = 'dikembalikan'"))['total'];
$total_denda = $total_denda ?? 0;

// Total Barang yang sedang dipinjam (dari detail_peminjaman)
$total_item_dipinjam = mysqli_fetch_assoc(mysqli_query($connect, "SELECT SUM(jumlah) AS total FROM detail_peminjaman dp 
    LEFT JOIN peminjaman p ON dp.id_peminjaman = p.id_peminjaman 
    WHERE p.status = 'dipinjam'"))['total'];
$total_item_dipinjam = $total_item_dipinjam ?? 0;

// Stok barang hampir habis (stok <= 3)
$stok_menipis = mysqli_query($connect, "SELECT id_barang, nama_barang, stok FROM barang WHERE stok <= 3 ORDER BY stok ASC LIMIT 5");
$total_stok_menipis = mysqli_num_rows($stok_menipis);

// ====================== DATA GRAFIK ======================

// Grafik peminjaman per bulan (tahun berjalan)
$grafik = mysqli_query($connect, "
    SELECT MONTH(tanggal_pinjam) AS bulan, COUNT(*) AS total 
    FROM peminjaman 
    WHERE YEAR(tanggal_pinjam) = YEAR(CURDATE())
    GROUP BY MONTH(tanggal_pinjam)
    ORDER BY bulan
");

$label_bulan = [];
$data_bulan  = [];
$nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

for ($i = 1; $i <= 12; $i++) {
    $label_bulan[] = $nama_bulan[$i - 1];
    $data_bulan[] = 0;
}

while ($g = mysqli_fetch_assoc($grafik)) {
    $data_bulan[$g['bulan'] - 1] = $g['total'];
}

// Grafik 5 barang terlaris
$terlaris = mysqli_query($connect, "
    SELECT b.nama_barang, SUM(dp.jumlah) AS total 
    FROM detail_peminjaman dp 
    LEFT JOIN barang b ON dp.id_barang = b.id_barang 
    LEFT JOIN peminjaman p ON dp.id_peminjaman = p.id_peminjaman 
    WHERE p.status = 'dikembalikan'
    GROUP BY dp.id_barang 
    ORDER BY total DESC 
    LIMIT 5
");

$label_terlaris = [];
$data_terlaris = [];
while ($t = mysqli_fetch_assoc($terlaris)) {
    $label_terlaris[] = $t['nama_barang'];
    $data_terlaris[] = $t['total'];
}

// ====================== DATA PEMINJAMAN TERBARU ======================
$peminjaman_terbaru = mysqli_query($connect, "
    SELECT p.*, u.username, b.nama_barang 
    FROM peminjaman p 
    LEFT JOIN users u ON p.id_user = u.id_user 
    LEFT JOIN barang b ON p.id_barang = b.id_barang 
    ORDER BY p.created_at DESC 
    LIMIT 5
");
?>

<?php include '../../partials/header.php' ?>

<body>
    <div class="container-scroller">
        <?php include '../../partials/navbar.php' ?>
        <div class="container-fluid page-body-wrapper">
            <?php include '../../partials/sidebar.php' ?>

            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-home"></i>
                            </span> Dashboard
                        </h3>
                    </div>

                    <!-- KARTU STATISTIK UTAMA - 4 KOLOM -->
                    <div class="row">
                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-2">Total Barang</h4>
                                            <h2 class="mb-0"><?= $total_barang ?></h2>
                                            <small class="opacity-75">Item tersedia</small>
                                        </div>
                                        <i class="mdi mdi-gamepad-variant mdi-36px"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-2">Pendapatan</h4>
                                            <h2 class="mb-0">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h2>
                                            <small class="opacity-75">+ denda Rp <?= number_format($total_denda, 0, ',', '.') ?></small>
                                        </div>
                                        <i class="mdi mdi-currency-usd mdi-36px"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-2">Sedang Dipinjam</h4>
                                            <h2 class="mb-0"><?= $total_dipinjam ?></h2>
                                            <small class="opacity-75"><?= $total_item_dipinjam ?> item</small>
                                        </div>
                                        <i class="mdi mdi-timer-sand mdi-36px"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-2">Selesai</h4>
                                            <h2 class="mb-0"><?= $total_kembali ?></h2>
                                            <small class="opacity-75"><?= $total_batal ?> batal</small>
                                        </div>
                                        <i class="mdi mdi-check-circle mdi-36px"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BARIS KEDUA - GRAFIK + STOK MENIPIS -->
                    <div class="row">
                        <div class="col-md-8 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Grafik Peminjaman <?= date('Y') ?></h4>
                                    <canvas id="chartPeminjaman" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <i class="mdi mdi-alert text-warning"></i> Stok Menipis
                                    </h4>
                                    <?php if ($total_stok_menipis > 0): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr><th>Barang</th><th>Stok</th><th>Status</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php while($row = mysqli_fetch_assoc($stok_menipis)): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                            <td class="text-center">
                                                                <?php if($row['stok'] <= 0): ?>
                                                                    <span class="badge bg-danger"><?= $row['stok'] ?></span>
                                                                <?php elseif($row['stok'] <= 2): ?>
                                                                    <span class="badge bg-warning"><?= $row['stok'] ?></span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-info"><?= $row['stok'] ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if($row['stok'] <= 0): ?>
                                                                    <span class="badge bg-danger">Habis</span>
                                                                <?php elseif($row['stok'] <= 2): ?>
                                                                    <span class="badge bg-warning">Segera</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-success">Aman</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="mdi mdi-package-variant mdi-48px text-success"></i>
                                            <p class="mt-2">Semua stok aman</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BARIS KETIGA - BARANG TERLARIS + PEMINJAMAN TERBARU -->
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <i class="mdi mdi-chart-line"></i> 5 Barang Terlaris
                                    </h4>
                                    <?php if (count($label_terlaris) > 0): ?>
                                        <canvas id="chartTerlaris" height="200"></canvas>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <i class="mdi mdi-chart-line mdi-48px text-muted"></i>
                                            <p>Belum ada data peminjaman selesai</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <i class="mdi mdi-clock"></i> Peminjaman Terbaru
                                        <a href="../peminjaman/index.php" class="btn btn-sm btn-outline-primary float-end">Lihat Semua</a>
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr><th>ID</th><th>Peminjam</th><th>Barang</th><th>Tgl Pinjam</th><th>Status</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php if (mysqli_num_rows($peminjaman_terbaru) > 0): ?>
                                                    <?php while($row = mysqli_fetch_assoc($peminjaman_terbaru)): ?>
                                                        <tr>
                                                            <td>#<?= $row['id_peminjaman'] ?></td>
                                                            <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                                                            <td><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                                                            <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                                            <td>
                                                                <?php
                                                                $badge = '';
                                                                switch($row['status']) {
                                                                    case 'dipinjam': $badge = 'badge bg-warning text-dark'; break;
                                                                    case 'dikembalikan': $badge = 'badge bg-success'; break;
                                                                    case 'batal': $badge = 'badge bg-danger'; break;
                                                                    default: $badge = 'badge bg-secondary';
                                                                }
                                                                ?>
                                                                <span class="<?= $badge ?> px-2 py-1"><?= ucfirst($row['status']) ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include '../../partials/footer.php' ?>
            </div>
        </div>
    </div>

    <?php include '../../partials/script.php' ?>

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Grafik Peminjaman Per Bulan
        const ctx1 = document.getElementById('chartPeminjaman').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?= json_encode($label_bulan) ?>,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: <?= json_encode($data_bulan) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } }
                }
            }
        });

        <?php if (count($label_terlaris) > 0): ?>
        // Grafik Barang Terlaris
        const ctx2 = document.getElementById('chartTerlaris').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= json_encode($label_terlaris) ?>,
                datasets: [{
                    label: 'Jumlah Dipinjam',
                    data: <?= json_encode($data_terlaris) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } }
                }
            }
        });
        <?php endif; ?>
    </script>

    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); }
        .bg-gradient-danger { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); }
        .card { border-radius: 12px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); }
        .stretch-card { display: flex; align-items: stretch; }
        .grid-margin { margin-bottom: 1.5rem; }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
        .badge { font-size: 0.7rem; padding: 0.25rem 0.5rem; border-radius: 30px; }
    </style>

</body>
</html>