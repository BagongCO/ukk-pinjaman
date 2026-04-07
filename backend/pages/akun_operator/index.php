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
   CEK ROLE (HANYA ADMIN)
================================ */
if ($_SESSION['role'] != 'admin') {
    header("Location: ../dashboard/index.php");
    exit;
}

/* ===============================
   AMBIL DATA OPERATOR
================================ */
$data = mysqli_query($connect, "
    SELECT users.*, roles.nama_role
    FROM users 
    JOIN roles ON users.id_role = roles.id_role
    WHERE roles.nama_role IN ('admin','petugas')
");

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
                                <i class="mdi mdi-account-multiple"></i>
                            </span>
                            Data Akun Operator
                        </h3>
                    </div>

                    <!-- ALERT -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php unset($_SESSION['success']);
                    endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php unset($_SESSION['error']);
                    endif; ?>


                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">

                                <h4 class="card-title">Daftar Akun Admin & Petugas</h4>

                                <button class="btn btn-gradient-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">

                                    + Tambah Akun

                                </button>

                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped">

                                    <thead class="table-light">

                                        <tr>
                                            <th width="50">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th width="150">Aksi</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php $no = 1; ?>
                                        <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                            <tr>

                                                <td><?= $no++ ?></td>

                                                <td><?= htmlspecialchars($row['nama']) ?></td>

                                                <td><?= htmlspecialchars($row['username']) ?></td>

                                                <td>

                                                    <?php if ($row['nama_role'] == 'admin'): ?>

                                                        <span class="badge bg-gradient-primary">Admin</span>

                                                    <?php else: ?>

                                                        <span class="badge bg-success">Petugas</span>

                                                    <?php endif; ?>

                                                </td>

                                                <td>

                                                    <button class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit<?= $row['id_user'] ?>">

                                                        Edit

                                                    </button>

                                                    <button class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalHapus<?= $row['id_user'] ?>">

                                                        Hapus

                                                    </button>

                                                </td>

                                            </tr>


                                            <!-- =============================
MODAL EDIT
============================= -->

                                            <div class="modal fade" id="modalEdit<?= $row['id_user'] ?>">

                                                <div class="modal-dialog modal-dialog-centered">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-warning text-white">

                                                            <h5 class="modal-title">Edit Akun</h5>

                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                        </div>

                                                        <form method="POST" action="proses/proses_edit.php">

                                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">

                                                            <div class="modal-body">

                                                                <div class="mb-3">

                                                                    <label>Nama</label>

                                                                    <input type="text"
                                                                        name="nama"
                                                                        value="<?= htmlspecialchars($row['nama']) ?>"
                                                                        class="form-control"
                                                                        required>

                                                                </div>

                                                                <div class="mb-3">

                                                                    <label>Username</label>

                                                                    <input type="text"
                                                                        name="username"
                                                                        value="<?= htmlspecialchars($row['username']) ?>"
                                                                        class="form-control"
                                                                        required>

                                                                </div>
                                                                <div class="mb-3">
                                                                    <label>Password (Kosongkan jika tidak diubah)</label>
                                                                    <input type="password"
                                                                        name="password"
                                                                        class="form-control"
                                                                        placeholder="Isi jika ingin mengganti password">
                                                                </div>

                                                                <div class="mb-3">

                                                                    <label>Role</label>

                                                                    <select name="id_role" class="form-control">

                                                                        <option value="1" <?= $row['id_role'] == 1 ? 'selected' : '' ?>>Admin</option>

                                                                        <option value="2" <?= $row['id_role'] == 2 ? 'selected' : '' ?>>Petugas</option>

                                                                    </select>

                                                                </div>

                                                            </div>

                                                            <div class="modal-footer">

                                                                <button class="btn btn-warning">Update</button>

                                                            </div>

                                                        </form>

                                                    </div>
                                                </div>
                                            </div>



                                            <!-- =============================
MODAL HAPUS
============================= -->

                                            <div class="modal fade" id="modalHapus<?= $row['id_user'] ?>">

                                                <div class="modal-dialog modal-dialog-centered">

                                                    <div class="modal-content">

                                                        <div class="modal-header bg-danger text-white">

                                                            <h5 class="modal-title">Hapus Akun</h5>

                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                                        </div>

                                                        <form method="POST" action="proses/proses_hapus.php">

                                                            <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">

                                                            <div class="modal-body">

                                                                Yakin ingin menghapus akun
                                                                <b><?= htmlspecialchars($row['nama']) ?></b> ?

                                                            </div>

                                                            <div class="modal-footer">

                                                                <button class="btn btn-danger">Hapus</button>

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


                    <!-- =============================
MODAL TAMBAH
============================= -->

                    <div class="modal fade" id="modalTambah">

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header bg-primary text-white">

                                    <h5 class="modal-title">Tambah Akun</h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                </div>

                                <form method="POST" action="proses/proses_tambah.php">

                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label>Nama</label>

                                            <input type="text" name="nama" class="form-control" required>

                                        </div>

                                        <div class="mb-3">

                                            <label>Username</label>

                                            <input type="text" name="username" class="form-control" required>

                                        </div>

                                        <div class="mb-3">

                                            <label>Password</label>

                                            <input type="password" name="password" class="form-control" required>

                                        </div>

                                        <div class="mb-3">

                                            <label>Role</label>

                                            <select name="id_role" class="form-control" required>

                                                <option value="">-- Pilih Role --</option>
                                                <option value="1">Admin</option>
                                                <option value="2">Petugas</option>

                                            </select>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

                                        <button class="btn btn-primary">Simpan</button>

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