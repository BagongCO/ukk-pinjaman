<?php
include '../../app.php';

$id = $_GET['id'] ?? 0;

// ambil detail peminjaman + user + barang
$query = mysqli_query($connect, "
    SELECT 
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.jam_pinjam,
        p.tanggal_kembali,
        p.jam_kembali,
        p.durasi_jam,
        p.total_harga,
        p.status,
        b.id_barang,
        b.nama_barang,
        b.harga_per_jam,
        b.foto,
        u.nama AS nama_user
    FROM peminjaman p
    JOIN barang b ON p.id_barang = b.id_barang
    JOIN users u ON p.id_user = u.id_user
    WHERE p.id_peminjaman = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}
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
                                <i class="mdi mdi-file-document"></i>
                            </span>
                            Detail Peminjaman
                        </h3>
                    </div>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Informasi Peminjaman</h4>

                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="../../../storage/barang/<?= $data['foto'] ?>" class="img-fluid rounded mb-3" width="200">
                                    <h5><?= htmlspecialchars($data['nama_barang']) ?></h5>
                                    <small class="text-muted">Rp <?= number_format($data['harga_per_jam']) ?> / jam</small>
                                </div>

                                <div class="col-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="200">Nama Peminjam</th>
                                            <td><?= htmlspecialchars($data['nama_user']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pinjam</th>
                                            <td><?= $data['tanggal_pinjam'] ?> <?= $data['jam_pinjam'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Durasi</th>
                                            <td><?= $data['durasi_jam'] ?> jam</td>
                                        </tr>
                                        <tr>
                                            <th>Total Harga</th>
                                            <td>Rp <?= number_format($data['total_harga']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <?php if ($data['status'] == 'dipinjam'): ?>
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                <?php elseif ($data['status'] == 'dikembalikan'): ?>
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Batal</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <?php if ($data['status'] == 'dikembalikan'): ?>
                                            <tr>
                                                <th>Dikembalikan</th>
                                                <td><?= $data['tanggal_kembali'] ?> <?= $data['jam_kembali'] ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>

                                    <div class="mt-3 d-flex gap-2">
                                        <a href="index.php" class="btn btn-secondary">← Kembali</a>

                                        <?php if ($data['status'] == 'dipinjam'): ?>
                                            <form action="proses/proses_kembalikan.php" method="POST" onsubmit="return confirm('Yakin barang sudah dikembalikan?')">
                                                <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman'] ?>">
                                                <input type="hidden" name="id_barang" value="<?= $data['id_barang'] ?>">
                                                <button class="btn btn-success">
                                                    ✔ Kembalikan Barang
                                                </button>
                                            </form>
                                        <?php endif; ?>
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
</body>

</html>