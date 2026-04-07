<?php
include '../../app.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/index.php");
    exit;
}

/* ===============================
   AMBIL DATA BARANG
================================ */
$data = mysqli_query($connect, "SELECT * FROM barang ORDER BY id_barang DESC");

if (!$data) {
    die("Query Error : " . mysqli_error($connect));
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

                    <!-- HEADER -->
                    <div class="page-header">

                        <h3 class="page-title">

                            <span class="page-title-icon bg-gradient-primary text-white me-2">

                                <i class="mdi mdi-gamepad-variant"></i>

                            </span>

                            Data Barang (PlayStation)

                        </h3>

                    </div>


                    <!-- ALERT SUCCESS -->
                    <?php if (isset($_SESSION['success'])): ?>

                        <div class="alert alert-success alert-dismissible fade show">

                            <?= htmlspecialchars($_SESSION['success']) ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                        </div>

                    <?php unset($_SESSION['success']);
                    endif; ?>


                    <div class="card">

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">

                                <h4 class="card-title">Daftar Barang</h4>

                                <button class="btn btn-gradient-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">

                                    + Tambah Barang

                                </button>

                            </div>


                            <div class="table-responsive">

                                <table class="table table-bordered table-striped align-middle" id="tableBarang">

                                    <thead class="table-light">

                                        <tr>

                                            <th width="50">No</th>
                                            <th>Foto</th>
                                            <th>Nama Barang</th>
                                            <th>Harga / Jam</th>
                                            <th>Stok</th>
                                            <th width="160">Aksi</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $no = 1; ?>

                                        <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                            <tr>

                                                <td><?= $no++ ?></td>

                                                <td>

                                                    <?php if (!empty($row['foto'])): ?>

                                                        <img src="../../../storage/barang/<?= htmlspecialchars($row['foto']) ?>"
                                                            width="70"
                                                            class="rounded">

                                                    <?php else: ?>

                                                        <span class="text-muted">Tidak ada foto</span>

                                                    <?php endif; ?>

                                                </td>

                                                <td><?= htmlspecialchars($row['nama_barang']) ?></td>

                                                <td>Rp <?= number_format($row['harga_per_jam'], 0, ',', '.') ?></td>

                                                <td><?= htmlspecialchars($row['stok']) ?></td>

                                                <td>

                                                    <button class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit<?= $row['id_barang'] ?>">

                                                        Edit

                                                    </button>


                                                    <button class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHapus<?= $row['id_barang'] ?>">

                                                        Hapus

                                                    </button>

                                                </td>

                                            </tr>


                                            <!-- ======================
MODAL EDIT
====================== -->

                                            <div class="modal fade"
                                                id="modalEdit<?= $row['id_barang'] ?>"
                                                tabindex="-1">

                                                <div class="modal-dialog modal-dialog-centered">

                                                    <div class="modal-content">


                                                        <div class="modal-header bg-gradient-primary text-white">

                                                            <h5 class="modal-title">

                                                                Edit Barang

                                                            </h5>

                                                            <button type="button"
                                                                class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal">

                                                            </button>

                                                        </div>


                                                        <form method="POST"
                                                            action="proses/proses_edit.php"
                                                            enctype="multipart/form-data">

                                                            <div class="modal-body">

                                                                <input type="hidden"
                                                                    name="id_barang"
                                                                    value="<?= $row['id_barang'] ?>">

                                                                <input type="hidden"
                                                                    name="foto_lama"
                                                                    value="<?= $row['foto'] ?>">


                                                                <div class="mb-3">

                                                                    <label>Foto Lama</label><br>

                                                                    <?php if (!empty($row['foto'])): ?>

                                                                        <img src="../../../storage/barang/<?= $row['foto'] ?>" width="80">

                                                                    <?php else: ?>

                                                                        <span class="text-muted">Tidak ada foto</span>

                                                                    <?php endif; ?>

                                                                </div>


                                                                <div class="mb-3">

                                                                    <label>Nama Barang</label>

                                                                    <input type="text"
                                                                        name="nama_barang"
                                                                        class="form-control"
                                                                        value="<?= htmlspecialchars($row['nama_barang']) ?>"
                                                                        required>

                                                                </div>


                                                                <div class="mb-3">

                                                                    <label>Harga / Jam</label>

                                                                    <input type="number"
                                                                        name="harga_per_jam"
                                                                        class="form-control"
                                                                        value="<?= $row['harga_per_jam'] ?>"
                                                                        required>

                                                                </div>


                                                                <div class="mb-3">

                                                                    <label>Stok</label>

                                                                    <input type="number"
                                                                        name="stok"
                                                                        class="form-control"
                                                                        value="<?= $row['stok'] ?>"
                                                                        required>

                                                                </div>


                                                                <div class="mb-3">

                                                                    <label>Ganti Foto (Opsional)</label>

                                                                    <input type="file"
                                                                        name="foto"
                                                                        class="form-control">

                                                                </div>

                                                            </div>


                                                            <div class="modal-footer">

                                                                <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">

                                                                    Batal

                                                                </button>

                                                                <button class="btn btn-gradient-primary">

                                                                    Simpan

                                                                </button>

                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>



                                            <!-- ======================
MODAL HAPUS
====================== -->

                                            <div class="modal fade"
                                                id="modalHapus<?= $row['id_barang'] ?>"
                                                tabindex="-1">

                                                <div class="modal-dialog modal-dialog-centered modal-sm">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-danger text-white">

                                                            <h5 class="modal-title">

                                                                Hapus Barang

                                                            </h5>

                                                            <button type="button"
                                                                class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal">

                                                            </button>

                                                        </div>


                                                        <form action="proses/proses_hapus.php"
                                                            method="POST">

                                                            <div class="modal-body text-center">

                                                                <input type="hidden"
                                                                    name="id_barang"
                                                                    value="<?= $row['id_barang'] ?>">

                                                                <p>

                                                                    Yakin hapus
                                                                    <b><?= htmlspecialchars($row['nama_barang']) ?></b> ?

                                                                </p>

                                                            </div>


                                                            <div class="modal-footer justify-content-center">

                                                                <button type="button"
                                                                    class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">

                                                                    Batal

                                                                </button>

                                                                <button class="btn btn-danger">

                                                                    Hapus

                                                                </button>

                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                        <?php endwhile; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>



                    <!-- ======================
MODAL TAMBAH
====================== -->

                    <div class="modal fade"
                        id="modalTambah"
                        tabindex="-1">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">


                                <div class="modal-header bg-gradient-primary text-white">

                                    <h5 class="modal-title">

                                        Tambah Barang

                                    </h5>

                                    <button type="button"
                                        class="btn-close btn-close-white"
                                        data-bs-dismiss="modal">

                                    </button>

                                </div>


                                <form action="proses/proses_tambah.php"
                                    method="POST"
                                    enctype="multipart/form-data">

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label>Nama Barang</label>

                                            <input type="text"
                                                name="nama_barang"
                                                class="form-control"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label>Harga / Jam</label>

                                            <input type="number"
                                                name="harga_per_jam"
                                                class="form-control"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label>Stok</label>

                                            <input type="number"
                                                name="stok"
                                                class="form-control"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label>Foto</label>

                                            <input type="file"
                                                name="foto"
                                                class="form-control"
                                                required>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">

                                            Batal

                                        </button>

                                        <button class="btn btn-gradient-primary">

                                            Simpan

                                        </button>

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