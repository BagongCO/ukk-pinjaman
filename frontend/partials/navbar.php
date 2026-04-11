<?php
// Cek session login
$isLoggedIn = isset($_SESSION['login']) && $_SESSION['login'] === true;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>
<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-lightbulb me-2"></i> Peminjaman Lampu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item"><a class="nav-link" href="/ukk-pinjaman/#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/ukk-pinjaman/#lampu">Lampu</a></li>
                <li class="nav-item"><a class="nav-link" href="/ukk-pinjaman/#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="/ukk-pinjaman/#contact">Contact</a></li>
                <?php if ($isLoggedIn && ($role == 'admin' || $role == 'petugas')): ?>
                    <li class="nav-item"><a class="nav-link" href="peminjam">Peminjaman</a></li>
                    <li class="nav-item"><a class="btn-outline-purple ms-2" href="logout">Logout</a></li>
                <?php elseif ($isLoggedIn && $role == 'peminjam'): ?>
                    <li class="nav-item"><a class="nav-link" href="peminjam">Peminjaman</a></li>
                    <li class="nav-item"><a class="btn-outline-purple ms-2" href="logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn-outline-purple ms-2" href="../backend/pages/auth/login.php">Login Area</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>