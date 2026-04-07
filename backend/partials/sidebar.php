<?php
$uri = $_SERVER['PHP_SELF'];

$id_user = $_SESSION['id_user'] ?? '';
$username = $_SESSION['username'] ?? '';
$role_id = $_SESSION['role_id'] ?? '';
$role_name = $_SESSION['role_name'] ?? '';

if (empty($role_name) && !empty($role_id)) {
    if ($role_id == 1) {
        $role_name = 'admin';
        $_SESSION['role_name'] = 'admin';
    } elseif ($role_id == 2) {
        $role_name = 'petugas';
        $_SESSION['role_name'] = 'petugas';
    } elseif ($role_id == 3) {
        $role_name = 'peminjam';
        $_SESSION['role_name'] = 'peminjam';
    }
}

function isAdmin()
{
    global $role_id, $role_name;
    return $role_id == 1 || strtolower($role_name) == 'admin';
}

function isPetugas()
{
    global $role_id, $role_name;
    return $role_id == 2 || strtolower($role_name) == 'petugas';
}

function isPeminjam()
{
    global $role_id, $role_name;
    return $role_id == 3 || strtolower($role_name) == 'peminjam';
}

function getRoleDisplayName()
{
    if (isAdmin()) return "Admin";
    if (isPetugas()) return "Petugas";
    if (isPeminjam()) return "Peminjam";
    return "User";
}

function isActive($keyword)
{
    global $uri;
    return strpos($uri, $keyword) !== false ? 'active-nav' : '';
}
?>
<style>
    /* VARIABEL */
    :root {
        --navbar-height: 60px;
        --sidebar-width: 260px;
    }

    /* SIDEBAR */
    .sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        min-height: calc(100vh - var(--navbar-height));
        padding: 20px 0;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        border-right: 1px solid #e2e8f0;
        position: fixed;
        width: var(--sidebar-width);
        top: var(--navbar-height);
        left: 0;
        z-index: 100;
        overflow-y: auto;
    }

    /* MAIN PANEL */
    .main-panel {
        margin-left: var(--sidebar-width);
        margin-top: 0;
        /* Hapus margin-top karena sudah di-handle oleh content-wrapper */
        width: calc(100% - var(--sidebar-width));
        min-height: calc(100vh - var(--navbar-height));
        background: #f8fafc;
        /* Sesuaikan dengan background halaman */
    }

    /* CONTENT WRAPPER */
    .content-wrapper {
        padding: 20px;
        background: #f8fafc;
        /* Pastikan background sama dengan main-panel */
        min-height: calc(100vh - var(--navbar-height));
    }

    /* Jika masih ada putih di bawah, tambahkan ini */
    body {
        background: #f8fafc;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* Pastikan navbar memiliki tinggi yang konsisten */
    .navbar {
        height: var(--navbar-height) !important;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* NAV LIST (sisa style sidebar Anda tetap sama) */
    .sidebar .nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* PROFILE */
    .nav-profile {
        padding: 0 20px;
        margin-bottom: 30px;
    }

    .nav-profile .nav-link {
        display: flex;
        align-items: center;
        padding: 10px;
        background: #f1f5f9;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .nav-profile-image img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 3px solid #8b5cf6;
    }

    .nav-profile-text {
        margin-left: 10px;
    }

    .font-weight-bold {
        font-weight: 600;
        color: #0f172a;
    }

    .text-secondary {
        font-size: 13px;
        color: #64748b;
    }

    /* ROLE BADGE */
    .role-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 20px;
        background: #e2e8f0;
        color: #475569;
        display: inline-block;
        margin-top: 4px;
    }

    .role-badge.admin {
        background: #8b5cf6;
        color: white;
    }

    .role-badge.petugas {
        background: #10b981;
        color: white;
    }

    .role-badge.peminjam {
        background: #f59e0b;
        color: white;
    }

    /* NAV ITEM */
    .sidebar .nav-item {
        padding: 5px 20px;
    }

    .sidebar .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 15px;
        color: #475569;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.3s;
    }

    .sidebar .nav-link i {
        font-size: 20px;
        margin-right: 12px;
        color: #64748b;
    }

    /* HOVER */
    .sidebar .nav-item:hover .nav-link {
        background: #f1f5f9;
        transform: translateX(5px);
        color: #8b5cf6;
    }

    /* ACTIVE MENU */
    .active-nav .nav-link {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white !important;
        box-shadow: 0 8px 20px rgba(139, 92, 246, 0.25);
    }

    .active-nav .nav-link i {
        color: white;
    }

    /* SECTION HEADER */
    .admin-section-header {
        padding: 15px 20px 5px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
    }

    /* LOGOUT */
    .logout {
        margin-top: 20px;
        border-top: 1px solid #e2e8f0;
        padding-top: 10px;
    }

    .logout .nav-link {
        color: #ef4444;
    }

    .logout:hover .nav-link {
        background: #fee2e2;
    }

    /* Reset untuk container utama */
    .container-fluid,
    .row {
        margin: 0;
        padding: 0;
    }
</style>

<nav class="sidebar">

    <ul class="nav">

        <!-- PROFILE -->
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">

                <div class="nav-profile-image">
                    <img src="../../template/assets/images/download.gif">
                </div>

                <div class="nav-profile-text">

                    <span class="font-weight-bold">
                        <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username'] ?? 'User') ?>
                    </span>

                    <div class="text-secondary">
                        <?= getRoleDisplayName() ?>
                    </div>

                    <span class="role-badge <?= strtolower(getRoleDisplayName()) ?>">
                        <?= getRoleDisplayName() ?>
                    </span>

                </div>

            </a>
        </li>

        <!-- DASHBOARD -->
        <li class="nav-item <?= isActive('dashboard') ?>">
            <a class="nav-link" href="../dashboard/index.php">
                <i class="mdi mdi-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <?php if (isAdmin()): ?>

            <li class="admin-section-header">Manajemen Sistem</li>

            <li class="nav-item <?= isActive('akun_operator') ?>">
                <a class="nav-link" href="../akun_operator/index.php">
                    <i class="mdi mdi-account-multiple"></i>
                    <span>Akun Operator</span>
                </a>
            </li>

            <li class="nav-item <?= isActive('log_aktivitas') ?>">
                <a class="nav-link" href="../log_aktivitas/index.php">
                    <i class="mdi mdi-history"></i>
                    <span>Log Aktivitas</span>
                </a>
            </li>

        <?php endif; ?>

        <?php if (isAdmin() || isPetugas()): ?>

            <li class="admin-section-header">Transaksi</li>

            <li class="nav-item <?= isActive('data_barang') ?>">
                <a class="nav-link" href="../data_barang/index.php">
                    <i class="mdi mdi-gamepad-variant"></i>
                    <span>Data Barang</span>
                </a>
            </li>

            <li class="nav-item <?= isActive('data_peminjaman') ?>">
                <a class="nav-link" href="../data_peminjaman/index.php">
                    <i class="mdi mdi-clipboard-list"></i>
                    <span>Peminjaman</span>
                </a>
            </li>

            <li class="nav-item <?= isActive('laporan_peminjaman') ?>">
                <a class="nav-link" data-bs-toggle="collapse" href="#laporanMenu">
                    <i class="mdi mdi-file-document"></i>
                    <span>Laporan Peminjaman</span>
                </a>

                <div class="collapse <?= isActive('peminjaman_bulanan') ? 'show' : '' ?>" id="laporanMenu">
                    <ul class="nav flex-column sub-menu">

                        <li class="nav-item <?= isActive('peminjaman_bulanan') ?>">
                            <a class="nav-link" href="../laporan_peminjaman/peminjaman_bulanan.php">
                                <i class="mdi mdi-chart-line"></i> Bulanan
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        <?php endif; ?>

        <?php if (isPeminjam()): ?>

            <li class="admin-section-header">Peminjaman</li>

            <li class="nav-item <?= isActive('peminjaman_saya') ?>">
                <a class="nav-link" href="../peminjaman_saya/index.php">
                    <i class="mdi mdi-clipboard-text"></i>
                    <span>Peminjaman Saya</span>
                </a>
            </li>

        <?php endif; ?>

        <!-- LOGOUT -->
        <li class="nav-item logout">
            <a class="nav-link" href="../auth/logout.php" onclick="return confirm('Yakin logout?')">
                <i class="mdi mdi-logout"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

</nav>