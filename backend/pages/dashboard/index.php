<?php
include '../../app.php';
include '../../partials/header.php';

// Ambil data statistik sederhana
$total_barang     = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM barang"))['total'];
$total_peminjaman = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman"))['total'];
$total_dipinjam   = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'"))['total'];
$total_kembali    = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dikembalikan'"))['total'];

// Data grafik peminjaman per bulan (tahun berjalan)
$grafik = mysqli_query($connect, "
    SELECT MONTH(tanggal_pinjam) AS bulan, COUNT(*) AS total 
    FROM peminjaman 
    WHERE YEAR(tanggal_pinjam) = YEAR(CURDATE())
    GROUP BY MONTH(tanggal_pinjam)
    ORDER BY bulan
");

$label_bulan = [];
$data_bulan  = [];

while ($g = mysqli_fetch_assoc($grafik)) {
    $label_bulan[] = date('M', mktime(0, 0, 0, $g['bulan'], 1));
    $data_bulan[]  = $g['total'];
}
?>

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
                            </span> Dashboard Rental PlayStation
                        </h3>
                    </div>

                    <!-- KARTU STATISTIK -->
                    <div class="row">
                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">
                                        Total Barang <i class="mdi mdi-gamepad-variant mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="mb-0"><?= $total_barang ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">
                                        Total Peminjaman <i class="mdi mdi-clipboard-list mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="mb-0"><?= $total_peminjaman ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-warning text-white">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">
                                        Sedang Dipinjam <i class="mdi mdi-timer-sand mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="mb-0"><?= $total_dipinjam ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 stretch-card grid-margin">
                            <div class="card bg-gradient-danger text-white">
                                <div class="card-body">
                                    <h4 class="font-weight-normal mb-3">
                                        Sudah Dikembalikan <i class="mdi mdi-check-circle mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="mb-0"><?= $total_kembali ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GRAFIK -->
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Grafik Peminjaman Per Bulan (Tahun Ini)</h4>
                                    <canvas id="chartPeminjaman"></canvas>
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

    <!-- SCRIPT GRAFIK -->
    <script>
        const ctx = document.getElementById('chartPeminjaman').getContext('2d');
        const chartPeminjaman = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($label_bulan) ?>,
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: <?= json_encode($data_bulan) ?>,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>