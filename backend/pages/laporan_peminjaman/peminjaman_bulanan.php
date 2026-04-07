<?php
include '../../app.php';

// proteksi login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// =======================
// QUERY DATA BULANAN
// =======================
$query = mysqli_query($connect, "
    SELECT 
        MONTH(tanggal_pinjam) as bulan,
        SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) as kembali,
        SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) as belum
    FROM peminjaman
    GROUP BY MONTH(tanggal_pinjam)
");

// =======================
// SET 12 BULAN
// =======================
$bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

// default isi 0
$dataBulanan = [];
foreach ($bulan as $i => $nama) {
    $dataBulanan[$i] = [
        'nama' => $nama,
        'kembali' => 0,
        'belum' => 0
    ];
}

// isi dari database
while ($row = mysqli_fetch_assoc($query)) {
    $b = $row['bulan'];
    $dataBulanan[$b]['kembali'] = $row['kembali'];
    $dataBulanan[$b]['belum'] = $row['belum'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include '../../partials/header.php'; ?>
    <title>Peminjaman Bulanan</title>
</head>

<body>

    <div class="container-scroller">

        <!-- NAVBAR -->
        <?php include '../../partials/navbar.php'; ?>

        <div class="container-fluid page-body-wrapper">

            <!-- SIDEBAR -->
            <?php include '../../partials/sidebar.php'; ?>

            <div class="main-panel">
                <div class="content-wrapper">

                    <!-- HEADER -->
                    <div class="page-header mb-4">
                        <h3 class="page-title fw-bold">
                            <i class="mdi mdi-calendar-month text-primary"></i> Peminjaman Bulanan
                        </h3>
                        <p class="text-muted">Rekap jumlah peminjaman setiap bulan</p>
                    </div>

                    <!-- CARD -->
                    <div class="card shadow-lg border-0" style="border-radius:15px;">
                        <div class="card-body">

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">

                                    <thead class="table-dark text-center">
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Sudah Dikembalikan</th>
                                            <th>Belum Dikembalikan</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($dataBulanan as $item):
                                        ?>
                                            <tr class="text-center">

                                                <td><?= $no++ ?></td>

                                                <!-- BULAN -->
                                                <td class="fw-semibold text-dark">
                                                    <?= $item['nama'] ?>
                                                </td>

                                                <!-- SUDAH KEMBALI -->
                                                <td>
                                                    <span class="badge bg-success px-3 py-2">
                                                        <i class="mdi mdi-check-circle"></i>
                                                        <?= $item['kembali'] ?>
                                                    </span>
                                                </td>

                                                <!-- BELUM KEMBALI -->
                                                <td>
                                                    <span class="badge bg-danger px-3 py-2">
                                                        <i class="mdi mdi-alert-circle"></i>
                                                        <?= $item['belum'] ?>
                                                    </span>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <?php include '../../partials/script.php'; ?>

</body>

</html>