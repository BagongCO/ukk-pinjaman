<?php
include '../../app.php';

// proteksi login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// ambil data log dengan JOIN ke tabel users dan roles untuk mendapatkan informasi yang lebih lengkap
$query = mysqli_query($connect, "
    SELECT 
        l.*,
        u.nama as user_nama,
        r.nama_role as user_role
    FROM log_aktivitas l
    LEFT JOIN users u ON l.id_user = u.id_user
    LEFT JOIN roles r ON u.id_role = r.id_role
    ORDER BY l.waktu DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <?php include '../../partials/header.php'; ?>
    <title>Log Aktivitas</title>
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
                            <i class="mdi mdi-history text-primary"></i> Log Aktivitas
                        </h3>
                        <p class="text-muted">Riwayat aktivitas pengguna dalam sistem</p>
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
                                            <th>ID User</th>
                                            <th>Username</th>
                                            <th>Nama Pengguna</th>
                                            <th>Role</th>
                                            <th>Aktivitas</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $no = 1;
                                        if (mysqli_num_rows($query) > 0) {
                                            while ($row = mysqli_fetch_assoc($query)) {
                                        ?>
                                                <tr class="text-center">

                                                    <td><?= $no++ ?></td>

                                                    <!-- ID USER -->
                                                    <td><?= $row['id_user'] ? $row['id_user'] : '-' ?></td>

                                                    <!-- USERNAME -->
                                                    <td class="fw-semibold text-dark">
                                                        <?= $row['username'] ? $row['username'] : '-' ?>
                                                    </td>

                                                    <!-- NAMA PENGGUNA (dari tabel users) -->
                                                    <td>
                                                        <?= $row['user_nama'] ? $row['user_nama'] : '-' ?>
                                                    </td>

                                                    <!-- ROLE (dari tabel roles) -->
                                                    <td>
                                                        <?php
                                                        $role = strtolower($row['role'] ? $row['role'] : ($row['user_role'] ?? ''));
                                                        if ($role == 'admin'):
                                                        ?>
                                                            <span class="badge bg-gradient-primary px-3 py-2">
                                                                <i class="mdi mdi-shield"></i> Admin
                                                            </span>
                                                        <?php elseif ($role == 'petugas'): ?>
                                                            <span class="badge bg-gradient-info px-3 py-2">
                                                                <i class="mdi mdi-account-tie"></i> Petugas
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary px-3 py-2">
                                                                <?= $row['role'] ? $row['role'] : '-' ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- AKTIVITAS -->
                                                    <td>
                                                        <?php
                                                        $aktivitas = strtolower($row['aktivitas']);
                                                        if (strpos($aktivitas, 'login') !== false):
                                                        ?>
                                                            <span class="badge bg-success px-3 py-2">
                                                                <i class="mdi mdi-login"></i> <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php elseif (strpos($aktivitas, 'logout') !== false): ?>
                                                            <span class="badge bg-danger px-3 py-2">
                                                                <i class="mdi mdi-logout"></i> <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php elseif (strpos($aktivitas, 'tambah') !== false || strpos($aktivitas, 'add') !== false): ?>
                                                            <span class="badge bg-primary px-3 py-2">
                                                                <i class="mdi mdi-plus"></i> <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php elseif (strpos($aktivitas, 'edit') !== false || strpos($aktivitas, 'update') !== false): ?>
                                                            <span class="badge bg-warning px-3 py-2">
                                                                <i class="mdi mdi-pencil"></i> <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php elseif (strpos($aktivitas, 'hapus') !== false || strpos($aktivitas, 'delete') !== false): ?>
                                                            <span class="badge bg-danger px-3 py-2">
                                                                <i class="mdi mdi-delete"></i> <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-info px-3 py-2">
                                                                <?= $row['aktivitas'] ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- WAKTU -->
                                                    <td class="text-muted">
                                                        <i class="mdi mdi-clock-outline me-1"></i>
                                                        <?= date('d M Y H:i:s', strtotime($row['waktu'])) ?>
                                                    </td>

                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo '<tr><td colspan="7" class="text-center py-4">Tidak ada data log aktivitas</td></tr>';
                                        }
                                        ?>
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