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
    <title>Peminjaman Lampu | Sewa Lampu Event & Dekorasi</title>

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: #fefbf7;
            color: #1e1e2a;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        /* Warna utama UNGU */
        :root {
            --purple-dark: #3c096c;
            --purple-primary: #5a189a;
            --purple-medium: #7b2cbf;
            --purple-light: #9d4edd;
            --purple-soft: #e0aaff;
            --purple-mist: #f3e8ff;
            --white: #ffffff;
            --cream: #fefbf7;
            --gray-light: #f5f5f5;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--purple-mist);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--purple-primary);
            border-radius: 10px;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 25px rgba(90, 24, 154, 0.08);
            padding: 0.9rem 0;
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            padding: 0.5rem 0;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--purple-primary), var(--purple-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
        }
        .navbar-brand i {
            background: none;
            -webkit-background-clip: unset;
            background-clip: unset;
            color: var(--purple-primary);
        }
        .nav-link {
            font-weight: 600;
            color: #2d2d3a;
            transition: 0.3s;
            margin: 0 0.2rem;
            border-radius: 50px;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--purple-primary);
            background: var(--purple-mist);
        }
        .btn-outline-purple {
            border: 2px solid var(--purple-primary);
            color: var(--purple-primary);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-outline-purple:hover {
            background: var(--purple-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(90, 24, 154, 0.3);
        }

        /* Hero Section */
        .hero {
            min-height: 90vh;
            background: linear-gradient(135deg, #f9f5ff 0%, #f0e6fa 50%, #e9daf5 100%);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: "💡";
            font-size: 25rem;
            position: absolute;
            bottom: -80px;
            right: -60px;
            opacity: 0.08;
            pointer-events: none;
            transform: rotate(15deg);
        }
        .hero::after {
            content: "✨";
            font-size: 18rem;
            position: absolute;
            top: -50px;
            left: -50px;
            opacity: 0.06;
            pointer-events: none;
        }
        .hero h1 {
            font-weight: 800;
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--purple-dark), var(--purple-medium), var(--purple-light));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.2;
            letter-spacing: -1px;
        }
        .hero .badge-custom {
            background: var(--purple-mist);
            color: var(--purple-primary);
            border-radius: 50px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .btn-gradient {
            background: linear-gradient(95deg, var(--purple-primary), var(--purple-light));
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2.2rem;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            box-shadow: 0 10px 25px rgba(90, 24, 154, 0.25);
        }
        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(90, 24, 154, 0.35);
            color: white;
        }
        .btn-outline-secondary-custom {
            border: 2px solid #cbd5e1;
            border-radius: 50px;
            padding: 0.9rem 1.8rem;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-outline-secondary-custom:hover {
            border-color: var(--purple-primary);
            color: var(--purple-primary);
            transform: translateY(-2px);
        }
        .hero-img {
            border-radius: 50px;
            box-shadow: 0 30px 50px rgba(0, 0, 0, 0.12);
            max-width: 100%;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .stat-hero {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 0.8rem 1rem;
        }

        /* Section Title */
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-title h2 {
            font-weight: 800;
            font-size: 2.2rem;
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
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-light));
            border-radius: 4px;
        }
        .section-title p {
            color: #6b7280;
            margin-top: 1.2rem;
            font-size: 1rem;
        }

        /* Card Lampu Premium */
        .card-lampu {
            border: none;
            border-radius: 28px;
            background: white;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            height: 100%;
            position: relative;
        }
        .card-lampu:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 45px rgba(90, 24, 154, 0.12);
        }
        .card-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--purple-primary);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
        }
        .card-img-top {
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--purple-mist), #f5eaff);
            padding: 25px;
            transition: transform 0.5s ease;
        }
        .card-lampu:hover .card-img-top {
            transform: scale(1.02);
        }
        .card-body {
            padding: 1.3rem 1.3rem 1.5rem;
        }
        .card-title {
            font-weight: 800;
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }
        .price {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--purple-primary);
        }
        .price small {
            font-size: 0.75rem;
            font-weight: 500;
            color: #94a3b8;
        }
        .stok-badge {
            font-size: 0.7rem;
            background: #dcfce7;
            color: #15803d;
            border-radius: 50px;
            padding: 0.25rem 0.9rem;
            font-weight: 600;
        }
        .stok-badge.habis {
            background: #fee2e2;
            color: #dc2626;
        }
        .btn-card {
            background: var(--purple-mist);
            color: var(--purple-primary);
            border-radius: 50px;
            font-weight: 700;
            transition: 0.3s;
            width: 100%;
            padding: 0.7rem;
        }
        .btn-card:hover {
            background: var(--purple-primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Stats Wrapper */
        .stats-wrapper {
            background: linear-gradient(135deg, var(--purple-mist), #f5eaff);
            border-radius: 60px;
            padding: 2.5rem 1.5rem;
            margin: 3rem 0;
        }
        .stat-item {
            text-align: center;
            padding: 0.5rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--purple-dark);
            line-height: 1;
        }
        .stat-label {
            color: #4b5563;
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 0.3rem;
        }
        .stat-icon {
            font-size: 2rem;
            color: var(--purple-light);
            margin-bottom: 0.5rem;
        }

        /* Testimoni Card */
        .testimoni-card {
            background: white;
            border-radius: 30px;
            padding: 1.8rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0e6fa;
            transition: 0.3s;
            height: 100%;
        }
        .testimoni-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(90, 24, 154, 0.08);
        }
        .testimoni-card .rating {
            color: #fbbf24;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .testimoni-card p {
            font-size: 0.9rem;
            line-height: 1.5;
            color: #4b5563;
        }
        .testimoni-card .user {
            font-weight: 700;
            color: var(--purple-dark);
            margin-top: 1rem;
        }

        /* CTA Banner */
        .cta-banner {
            background: linear-gradient(135deg, var(--purple-primary), var(--purple-medium));
            border-radius: 40px;
            padding: 3rem;
            text-align: center;
            color: white;
        }
        .cta-banner h3 {
            font-weight: 800;
            font-size: 1.8rem;
        }
        .btn-cta {
            background: white;
            color: var(--purple-primary);
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 700;
            transition: 0.3s;
        }
        .btn-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            color: var(--purple-dark);
        }

        /* Footer */
        footer {
            background: #1a1a2e;
            color: #a1a1aa;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }
        footer h5, footer h6 {
            color: white;
        }
        footer a {
            text-decoration: none;
            transition: 0.3s;
        }
        footer a:hover {
            color: var(--purple-light) !important;
        }

        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--purple-primary);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
            z-index: 99;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        .back-to-top:hover {
            background: var(--purple-dark);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.2rem; }
            .hero { text-align: center; min-height: auto; padding: 4rem 0; }
            .stats-wrapper { border-radius: 35px; }
            .card-img-top { height: 170px; }
            .section-title h2 { font-size: 1.8rem; }
            .cta-banner { padding: 2rem; }
            .cta-banner h3 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-lightbulb me-2"></i> Peminjaman Lampu
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lampu">Lampu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="btn-outline-purple ms-2" href="../ukk-pinjaman/backend/pages/auth/login.php">Login Area</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="beranda">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="badge-custom d-inline-flex mb-4">
                        <i class="fas fa-bolt me-2"></i> Sewa Lampu Profesional
                    </div>
                    <h1 class="mb-4">Terangi Acara Anda<br>dengan Lampu Berkualitas</h1>
                    <p class="text-muted mb-4 lead fs-5">Pilihan lampu dekorasi, panggung, dan event dengan harga transparan. Bebas desain, garansi terbaik.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#lampu" class="btn-gradient">Lihat Koleksi <i class="fas fa-arrow-right ms-2"></i></a>
                        <a href="#" class="btn-outline-secondary-custom">Hubungi Kami</a>
                    </div>
                    <div class="row mt-5 g-3">
                        <div class="col-4">
                            <div class="stat-hero">
                                <span class="fw-bold fs-3 text-purple-primary">+<?= $total_sewa ?></span><br>
                                <small class="text-secondary">Sewa Selesai</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-hero">
                                <span class="fw-bold fs-3 text-purple-primary"><?= $total_lampu ?></span><br>
                                <small class="text-secondary">Unit Lampu</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-hero">
                                <span class="fw-bold fs-3 text-purple-primary">24/7</span><br>
                                <small class="text-secondary">Dukungan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left" data-aos-duration="1000">
                    <img src="https://placehold.co/600x500/7b2cbf/ffffff?text=Lampu+Dekoratif&font=montserrat" alt="hero lampu" class="hero-img img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- LIST LAMPU -->
    <section class="py-5" id="lampu">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2><i class="fas fa-lightbulb me-2"></i> Lampu Tersedia</h2>
                <p>Pilihan terbaik untuk kebutuhan cahaya anda</p>
            </div>
            <div class="row g-4">
                <?php if (mysqli_num_rows($query_lampu) > 0): ?>
                    <?php while ($l = mysqli_fetch_assoc($query_lampu)): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $no_delay ?? 100 ?>">
                            <div class="card card-lampu">
                                <div class="card-badge"><i class="fas fa-star me-1"></i> Terbaru</div>
                                <img src="<?= (!empty($l['foto']) && file_exists('backend/storage/barang/' . $l['foto'])) ? 'backend/storage/barang/' . $l['foto'] : 'https://placehold.co/400x250/7b2cbf/ffffff?text=' . urlencode($l['nama_barang']) ?>" class="card-img-top" alt="<?= htmlspecialchars($l['nama_barang']) ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($l['nama_barang']) ?></h5>
                                        <span class="stok-badge <?= $l['stok'] <= 0 ? 'habis' : '' ?>">
                                            <i class="fas fa-boxes me-1"></i> stok <?= $l['stok'] ?>
                                        </span>
                                    </div>
                                    <div class="price mt-2">Rp <?= number_format($l['harga_per_jam'], 0, ',', '.') ?> <small>/ jam</small></div>
                                    <p class="text-muted small mt-2"><?= substr(htmlspecialchars($l['deskripsi'] ?? 'Lampu berkualitas untuk berbagai acara'), 0, 70) ?>...</p>
                                    <a href="../backend/pages/peminjaman/create.php?barang=<?= $l['id_barang'] ?>" class="btn btn-card mt-3"><i class="fas fa-calendar-check me-2"></i> Sewa Sekarang</a>
                                </div>
                            </div>
                        </div>
                        <?php $no_delay = ($no_delay ?? 100) + 100; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-lightbulb fa-4x text-muted mb-3"></i>
                        <h5 class="mt-3">Belum ada lampu tersedia</h5>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="../backend/pages/barang/index.php" class="btn btn-outline-purple rounded-pill px-5 py-2">Lihat Semua Lampu <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- STATISTIK -->
    <div class="container">
        <div class="stats-wrapper" data-aos="zoom-in">
            <div class="row text-center">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-lightbulb"></i></div>
                        <div class="stat-number"><?= $total_lampu ?></div>
                        <div class="stat-label">Total Lampu</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-tags"></i></div>
                        <div class="stat-number"><?= $total_jenis ?></div>
                        <div class="stat-label">Jenis Lampu</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-3 mt-md-0">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-number"><?= $total_sewa ?></div>
                        <div class="stat-label">Sewa Sukses</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mt-3 mt-md-0">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-number"><?= $total_dipinjam ?></div>
                        <div class="stat-label">Sedang Dipinjam</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TENTANG + TESTIMONI -->
    <section class="py-4" id="tentang">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-md-6" data-aos="fade-right">
                    <img src="https://placehold.co/600x450/7b2cbf/ffffff?text=Koleksi+Lampu+Kami&font=montserrat" alt="tentang" class="img-fluid rounded-4 shadow-lg">
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <span class="badge bg-purple-soft mb-3" style="background:#e0aaff; color:#3c096c; padding:0.5rem 1rem;">Tentang Kami</span>
                    <h2 class="fw-bold fs-1 mb-3">Sewa Lampu <br><span class="text-purple-primary">Praktis & Modern</span></h2>
                    <p class="text-muted">Peminjaman Lampu hadir untuk memberikan solusi kebutuhan pencahayaan acara Anda. Dari pernikahan, konser, hingga dekorasi rumah. Kami menyediakan lampu berkualitas dengan sistem sewa yang mudah dan transparan.</p>
                    <div class="d-flex flex-wrap gap-4 mt-4">
                        <div><i class="fas fa-check-circle text-purple-primary me-2"></i> Pengiriman tepat waktu</div>
                        <div><i class="fas fa-check-circle text-purple-primary me-2"></i> Lampu terawat</div>
                        <div><i class="fas fa-check-circle text-purple-primary me-2"></i> Harga bersahabat</div>
                    </div>
                    <div class="mt-4 d-flex gap-3">
                        <a href="#" class="btn btn-outline-purple rounded-pill px-4"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
                        <a href="#" class="btn btn-outline-purple rounded-pill px-4"><i class="fab fa-instagram me-2"></i>Instagram</a>
                    </div>
                </div>
            </div>

            <!-- Testimoni -->
            <div class="row mt-5 g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimoni-card">
                        <div class="rating">★★★★★</div>
                        <p class="mb-0">“Lampu sangat bagus, harga bersahabat. Acara wedding jadi lebih berkesan. Pelayanan ramah dan cepat!”</p>
                        <div class="user mt-3">— Dina & Andri</div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimoni-card">
                        <div class="rating">★★★★★</div>
                        <p class="mb-0">“Proses sewa mudah, pengiriman cepat, lampu sesuai pesanan. Recomended untuk event organizer!”</p>
                        <div class="user mt-3">— Reza Pratama</div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimoni-card">
                        <div class="rating">★★★★☆</div>
                        <p class="mb-0">“Lampu variatif, staf ramah dan membantu. Pasti sewa lagi untuk acara berikutnya.”</p>
                        <div class="user mt-3">— Citra Kirana</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="py-5 my-3">
        <div class="container">
            <div class="cta-banner" data-aos="flip-up">
                <h3 class="mb-3">✨ Siap untuk acara Anda? ✨</h3>
                <p class="mb-4 opacity-75">Dapatkan penawaran terbaik untuk sewa lampu dalam jumlah besar.</p>
                <a href="../ukk-pinjaman/backend/pages/auth/login.php" class="btn-cta">Sewa Sekarang <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i> Peminjaman Lampu</h5>
                    <p class="text-muted small">Sewa lampu profesional untuk berbagai event. Terbaik, terpercaya, dan modern.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h6 class="text-white mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none small">Beranda</a></li>
                        <li><a href="#lampu" class="text-muted text-decoration-none small">Lampu</a></li>
                        <li><a href="#tentang" class="text-muted text-decoration-none small">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Kontak</h6>
                    <p class="text-muted small mb-2"><i class="fas fa-phone-alt me-2"></i> +62 812 3456 7890</p>
                    <p class="text-muted small"><i class="fab fa-whatsapp me-2"></i> +62 812 3456 7890</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="text-white mb-3">Jam Operasional</h6>
                    <p class="text-muted small mb-0">Senin - Sabtu: 09.00 - 21.00</p>
                    <p class="text-muted small">Minggu & Hari Libur: Tutup</p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center small text-muted">© 2026 Peminjaman Lampu - Sewa Lampu Modern. All rights reserved.</div>
        </div>
    </footer>

    <!-- Back to Top -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            const backTop = document.getElementById('backToTop');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                backTop.classList.add('show');
            } else {
                navbar.classList.remove('scrolled');
                backTop.classList.remove('show');
            }
        });

        // Back to top
        document.getElementById('backToTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>