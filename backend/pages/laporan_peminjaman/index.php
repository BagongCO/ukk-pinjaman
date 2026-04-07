<?php
include '../../app.php';

$data = mysqli_query($connect, "
    SELECT 
        p.*, 
        u.nama AS nama_user,
        b.nama_barang,
        b.foto
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN barang b ON p.id_barang = b.id_barang
    ORDER BY p.id_peminjaman DESC
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
                                <i class="mdi mdi-clipboard-text"></i>
                            </span>
                            Data Peminjaman
                        </h3>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Barang</th>
                                            <th>Peminjam</th>
                                            <th>Tgl Pinjam</th>
                                            <th>Durasi (Jam)</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Cetak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        while ($row = mysqli_fetch_assoc($data)): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <img src="../../../storage/barang/<?= $row['foto'] ?>" width="60" class="rounded">
                                                </td>
                                                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                                <td><?= $row['tanggal_pinjam'] ?></td>
                                                <td><?= $row['durasi_jam'] ?> Jam</td>
                                                <td>Rp <?= number_format($row['total_harga']) ?></td>
                                                <td>
                                                    <span class="badge 
                    <?= $row['status'] == 'dipinjam' ? 'bg-warning' : ($row['status'] == 'dikembalikan' ? 'bg-success' : 'bg-danger') ?>">
                                                        <?= $row['status'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row['status'] == 'dikembalikan'): ?>
                                                        <a target="_blank"
                                                            href="cetak.php?id=<?= $row['id_peminjaman'] ?>"
                                                            class="btn btn-sm btn-secondary">
                                                            Cetak
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <?php include '../../partials/footer.php' ?>
            </div>
        </div>
    </div>

    <?php include '../../partials/script.php' ?>
</body>

</html>