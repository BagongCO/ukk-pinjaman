<?php
include "config/connection.php";

// Ambil data lampu terbaru (max 6)
$query_lampu = mysqli_query($connect, "SELECT * FROM barang WHERE stok > 0 ORDER BY id_barang DESC LIMIT 6");

// Hitung statistik
$total_lampu = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM barang"))['total'];
$total_jenis = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(DISTINCT id_barang) as total FROM barang"))['total'];
$total_sewa = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dikembalikan'"))['total'];
$total_dipinjam = mysqli_fetch_assoc(mysqli_query($connect, "SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Peminjaman Lampu - Sewa Lampu Event & Dekorasi</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #fefbff;
            color: #1e1e2f;
            scroll-behavior: smooth;
        }

        /* Warna utama ungu */
        :root {
            --purple-dark: #4a148c;
            --purple-primary: #6a1b9a;
            --purple-light: #9c27b0;
            --purple-soft: #f3e5f5;
            --purple-mist: #ede7f6;
            --white: #ffffff;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 20px rgba(106, 27, 154, 0.08);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            background: linear-gradient(135deg, var(--purple-primary), var(--purple-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .nav-link {
            font-weight: 500;
            color: #2c2c3a;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--purple-primary);
        }

        .btn-outline-purple {
            border: 2px solid var(--purple-primary);
            color: var(--purple-primary);
            border-radius: 40px;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-outline-purple:hover {
            background: var(--purple-primary);
            color: white;
        }

        /* Hero Section */
        .hero {
            min-height: 88vh;
            background: linear-gradient(125deg, #f9f5ff 0%, #ede4fa 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "✨";
            font-size: 22rem;
            position: absolute;
            bottom: -50px;
            right: -80px;
            opacity: 0.1;
            pointer-events: none;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 3.2rem;
            background: linear-gradient(135deg, var(--purple-dark), var(--purple-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.2;
        }

        .hero .badge-light {
            background: var(--purple-soft);
            color: var(--purple-primary);
            border-radius: 40px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
        }

        .btn-gradient {
            background: linear-gradient(95deg, var(--purple-primary), var(--purple-light));
            border: none;
            border-radius: 40px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            color: white;
            transition: 0.2s;
            box-shadow: 0 8px 18px rgba(106, 27, 154, 0.25);
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(106, 27, 154, 0.3);
            color: white;
        }

        .hero-img {
            border-radius: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            max-width: 100%;
        }

        /* Section title */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-weight: 800;
            font-size: 2rem;
            color: var(--purple-dark);
            position: relative;
            display: inline-block;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-light));
            border-radius: 3px;
        }

        /* Card lampu compact */
        .card-lampu {
            border: none;
            border-radius: 28px;
            background: white;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            height: 100%;
        }

        .card-lampu:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px rgba(106, 27, 154, 0.12);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            background: var(--purple-soft);
            padding: 20px;
        }

        .card-body {
            padding: 1.2rem 1.2rem 1.5rem;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .price {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--purple-primary);
        }

        .price small {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6c757d;
        }

        .stok-badge {
            font-size: 0.7rem;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 50px;
            padding: 0.2rem 0.8rem;
        }

        .btn-card {
            background: var(--purple-soft);
            color: var(--purple-primary);
            border-radius: 40px;
            font-weight: 600;
            transition: 0.2s;
            width: 100%;
        }

        .btn-card:hover {
            background: var(--purple-primary);
            color: white;
        }

        /* Statistik */
        .stats-wrapper {
            background: var(--purple-mist);
            border-radius: 48px;
            padding: 2rem 1rem;
            margin: 3rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--purple-dark);
        }

        /* Testimoni mini */
        .testimoni-card {
            background: white;
            border-radius: 28px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0e9fa;
        }

        .fas.fa-star {
            color: #ffc107;
        }

        /* Footer */
        footer {
            background: #1e1a2f;
            color: #ccc;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }

            .hero {
                text-align: center;
                min-height: auto;
                padding: 4rem 0;
            }

            .stats-wrapper {
                border-radius: 28px;
            }

            .card-img-top {
                height: 160px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-lightbulb me-2" style="color: #6a1b9a;"></i> Peminjaman Lampu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lampu">Lampu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="btn-outline-purple ms-2" href="../backend/pages/auth/index.php">Login Area</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="badge-light d-inline-flex mb-3">
                        <i class="fas fa-bolt me-2"></i> Sewa Lampu Profesional
                    </div>
                    <h1 class="mb-3">Terangi Acara Anda <br>dengan Lampu Berkualitas</h1>
                    <p class="text-muted mb-4 lead">Pilihan lampu dekorasi, panggung, dan event dengan harga transparan. Bebas desain, garansi terbaik.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#lampu" class="btn-gradient">Lihat Koleksi <i class="fas fa-arrow-right ms-2"></i></a>
                        <a href="#" class="btn btn-outline-secondary rounded-pill px-4">Hubungi Kami</a>
                    </div>
                    <div class="row mt-5">
                        <div class="col-4">
                            <span class="fw-bold">+<?= $total_sewa ?></span><br>
                            <small class="text-secondary">Sewa terselesaikan</small>
                        </div>
                        <div class="col-4">
                            <span class="fw-bold"><?= $total_lampu ?></span><br>
                            <small class="text-secondary">Unit lampu</small>
                        </div>
                        <div class="col-4">
                            <span class="fw-bold">24/7</span><br>
                            <small class="text-secondary">Dukungan</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://placehold.co/600x500/f3e5f5/6a1b9a?text=Lampu+Dekoratif&font=montserrat" alt="hero lampu" class="hero-img img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- LIST LAMPU (COMPACT) -->
    <section class="py-5" id="lampu">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-lightbulb me-2"></i> Lampu Tersedia</h2>
                <p class="text-muted">Pilihan terbaik untuk kebutuhan cahaya anda</p>
            </div>
            <div class="row g-4">
                <?php if (mysqli_num_rows($query_lampu) > 0): ?>
                    <?php while ($l = mysqli_fetch_assoc($query_lampu)): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-lampu">
                                <img src="<?= (!empty($l['foto']) && file_exists('backend/storage/barang/' . $l['foto'])) ? 'backend/storage/barang/' . $l['foto'] : 'https://placehold.co/400x250/ede7f6/6a1b9a?text=' . urlencode($l['nama_barang']) ?>" class="card-img-top" alt="<?= htmlspecialchars($l['nama_barang']) ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($l['nama_barang']) ?></h5>
                                        <span class="stok-badge"><i class="fas fa-boxes me-1"></i> stok <?= $l['stok'] ?></span>
                                    </div>
                                    <div class="price mt-2">Rp <?= number_format($l['harga_per_jam'], 0, ',', '.') ?> <small>/ jam</small></div>
                                    <p class="text-muted small mt-2"><?= substr(htmlspecialchars($l['deskripsi'] ?? 'Lampu berkualitas untuk berbagai acara'), 0, 70) ?>...</p>
                                    <a href="../backend/pages/peminjaman/create.php?barang=<?= $l['id_barang'] ?>" class="btn btn-card mt-3"><i class="fas fa-calendar-check me-2"></i> Sewa Sekarang</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-lightbulb fa-3x text-muted"></i>
                        <h5 class="mt-3">Belum ada lampu tersedia</h5>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-5">
                <a href="../backend/pages/barang/index.php" class="btn btn-outline-purple rounded-pill px-4">Lihat Semua Lampu <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- STATISTIK COMPACT -->
    <div class="container">
        <div class="stats-wrapper">
            <div class="row text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_lampu ?></div>
                        <div class="text-muted">Total Lampu</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_jenis ?></div>
                        <div class="text-muted">Jenis Lampu</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-3 mt-md-0">
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_sewa ?></div>
                        <div class="text-muted">Sewa Sukses</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-3 mt-md-0">
                    <div class="stat-item">
                        <div class="stat-number"><?= $total_dipinjam ?></div>
                        <div class="text-muted">Sedang Dipinjam</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TENTANG + TESTIMONI (compact) -->
    <section class="py-4" id="tentang">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6">
                    <img src="https://placehold.co/600x450/ede7f6/4a148c?text=Koleksi+Lampu+Kami&font=montserrat" alt="tentang" class="img-fluid rounded-4 shadow-sm">
                </div>
                <div class="col-md-6">
                    <span class="badge bg-purple-soft text-purple-primary mb-2" style="background:#f3e5f5; color:#6a1b9a;">Tentang Kami</span>
                    <h2 class="fw-bold">Sewa Lampu Praktis & Modern</h2>
                    <p class="text-muted mt-3">Web ini hadir untuk memberikan solusi kebutuhan pencahayaan acara Anda. Dari pernikahan, konser, hingga dekorasi rumah. Kami menyediakan lampu berkualitas dengan sistem sewa yang mudah dan transparan.</p>
                    <div class="d-flex gap-3 mt-4">
                        <div><i class="fas fa-check-circle text-purple-primary"></i> Pengiriman tepat waktu</div>
                        <div><i class="fas fa-check-circle text-purple-primary"></i> Lampu terawat</div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <i class="fab fa-whatsapp fa-2x text-success"></i>
                        <i class="fab fa-instagram fa-2x text-purple-primary"></i>
                        <i class="fab fa-facebook fa-2x text-primary"></i>
                    </div>
                </div>
            </div>

            <!-- Testimoni singkat -->
            <div class="row mt-5 g-3">
                <div class="col-md-4">
                    <div class="testimoni-card">
                        <div class="mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="small">“Lampu sangat bagus, harga bersahabat. Acara wedding jadi lebih berkesan.”</p>
                        <strong>— Dina</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimoni-card">
                        <div class="mb-2">⭐⭐⭐⭐⭐</div>
                        <p class="small">“Proses sewa mudah, pengiriman cepat. Recomended!”</p>
                        <strong>— Reza</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimoni-card">
                        <div class="mb-2">⭐⭐⭐⭐</div>
                        <p class="small">“Lampu variatif, staf ramah. Pasti sewa lagi.”</p>
                        <strong>— Citra</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="py-5 my-3">
        <div class="container">
            <div class="bg-purple-soft rounded-4 p-5 text-center" style="background: linear-gradient(105deg, #f3e5f5, #e1d5f5);">
                <h3 class="fw-bold">Siap untuk acara Anda?</h3>
                <p class="mb-4">Dapatkan penawaran terbaik untuk sewa lampu dalam jumlah besar.</p>
                <a href="../backend/pages/peminjaman/create.php" class="btn-gradient px-5 py-2">Sewa Sekarang</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-white"><i class="fas fa-lightbulb me-2"></i> peminjaman Lampu</h5>
                    <p class="text-muted small">Sewa lampu profesional untuk berbagai event. Terbaik, terpercaya, dan modern.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-white">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none small">Beranda</a></li>
                        <li><a href="#lampu" class="text-muted text-decoration-none small">Lampu</a></li>
                        <li><a href="#" class="text-muted text-decoration-none small">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white">Kontak</h6>
                    <p class="text-muted small"><i class="fas fa-phone-alt me-2"></i> +62 812 3456 7890</p>
                    <p class="text-muted small"><i class="fab fa-whatsapp me-2"></i> +62 812 3456 7890</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white">Jam Operasional</h6>
                    <p class="text-muted small">Senin - Sabtu: 09.00 - 21.00<br>Minggu/Tutup</p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center small text-muted">© 2026 Peminjaman Lampu - Sewa Lampu Modern</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>