<?php
include '../../app.php';

$data = mysqli_query($connect, "
    SELECT 
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.jam_pinjam,
        p.durasi_jam,
        p.total_harga,
        p.status,
        b.nama_barang,
        b.foto,
        u.nama AS nama_user
    FROM peminjaman p
    JOIN barang b ON p.id_barang = b.id_barang
    JOIN users u ON p.id_user = u.id_user
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
                                <i class="mdi mdi-clipboard-list"></i>
                            </span>
                            Data Peminjaman PS
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
                            <div class="d-flex justify-content-between mb-3">
                                <h4 class="card-title">Daftar Peminjaman</h4>
                                <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    + Tambah Peminjaman
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="tablePeminjaman">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Foto</th>
                                            <th>Barang</th>
                                            <th>Peminjam</th>
                                            <th>Tgl Pinjam</th>
                                            <th>Durasi</th>
                                            <th>Total Harga</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        while ($row = mysqli_fetch_assoc($data)): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <img src="../../../storage/barang/<?= htmlspecialchars($row['foto']) ?>" width="70" class="rounded">
                                                </td>
                                                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                                <td><?= $row['tanggal_pinjam'] ?> <?= $row['jam_pinjam'] ?></td>
                                                <td><?= $row['durasi_jam'] ?> Jam</td>
                                                <td>Rp <?= number_format($row['total_harga']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $row['status'] == 'dipinjam' ? 'warning' : 'success' ?>">
                                                        <?= ucfirst($row['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="detail.php?id=<?= $row['id_peminjaman'] ?>" class="btn btn-sm btn-info">Detail</a>
                                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- MODAL TAMBAH -->
                    <div class="modal fade" id="modalTambah">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content card">
                                <div class="modal-header bg-gradient-primary text-white">
                                    <h5 class="modal-title">Tambah Peminjaman</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <form method="POST" action="proses/proses_tambah.php">
                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label>Barang</label>
                                            <select name="id_barang" class="form-control" required>
                                                <option value="">-- Pilih Barang --</option>
                                                <?php
                                                $barang = mysqli_query($connect, "SELECT * FROM barang");
                                                while ($b = mysqli_fetch_assoc($barang)) {
                                                    echo "<option value='{$b['id_barang']}'>{$b['nama_barang']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Peminjam</label>
                                            <select name="id_user" class="form-control" required>
                                                <option value="">-- Pilih User --</option>
                                                <?php
                                                $users = mysqli_query($connect, "SELECT * FROM users");
                                                while ($u = mysqli_fetch_assoc($users)) {
                                                    echo "<option value='{$u['id_user']}'>{$u['nama']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Tanggal Pinjam</label>
                                            <input type="date" name="tanggal_pinjam" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Jam Pinjam</label>
                                            <input type="time" name="jam_pinjam" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Durasi (Jam)</label>
                                            <input type="number" name="durasi_jam" class="form-control" required>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-gradient-primary">Simpan</button>
                                    </div>

                                </form>
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