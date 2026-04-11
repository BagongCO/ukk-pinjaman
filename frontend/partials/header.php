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
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        .nav-link:hover,
        .nav-link.active {
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

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .stat-hero {
            background: rgba(255, 255, 255, 0.7);
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

        footer h5,
        footer h6 {
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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
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
            .hero h1 {
                font-size: 2.2rem;
            }

            .hero {
                text-align: center;
                min-height: auto;
                padding: 4rem 0;
            }

            .stats-wrapper {
                border-radius: 35px;
            }

            .card-img-top {
                height: 170px;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .cta-banner {
                padding: 2rem;
            }

            .cta-banner h3 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

