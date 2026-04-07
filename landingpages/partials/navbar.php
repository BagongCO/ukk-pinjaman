<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            🎮 Rental PS EON
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="daftar_ps.php">PlayStation</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="https://www.instagram.com/adiwinata208/?hl=en" target="_blank">Kontak</a>
                </li>

                <?php if (isset($_SESSION['id_user'])): ?>

                    <!-- Jika sudah login -->
                    <li class="nav-item dropdown">
                        <a class="btn btn-success dropdown-toggle ms-3" data-bs-toggle="dropdown">
                            <?= $_SESSION['username']; ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="profil.php">
                                    Profil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="auth/logout.php"
                                    onclick="return confirm('Yakin ingin logout?')">
                                    Logout
                                </a>
                            </li>

                        </ul>
                    </li>

                <?php else: ?>

                    <!-- Jika belum login -->
                    <li class="nav-item">
                        <a class="btn btn-primary ms-3" href="auth/login.php">
                            Login
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>