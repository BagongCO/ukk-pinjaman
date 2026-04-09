<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Peminjaman Lampu</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../template/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../template/assets/css/style.css">
    <link rel="shortcut icon" href="../../template/assets/images/favicon-lamp.png" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', 'Poppins', system-ui, sans-serif;
        }

        .login-container {
            height: 100vh;
            overflow: hidden;
        }

        /* LEFT SIDE - UNGU GRADIENT */
        .login-left {
            background: linear-gradient(135deg, #4a148c, #6a1b9a, #9c27b0);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-left::before {
            content: "💡";
            font-size: 20rem;
            position: absolute;
            bottom: -50px;
            right: -50px;
            opacity: 0.08;
            pointer-events: none;
            transform: rotate(15deg);
        }

        .login-left::after {
            content: "✨";
            font-size: 15rem;
            position: absolute;
            top: -30px;
            left: -30px;
            opacity: 0.08;
            pointer-events: none;
        }

        .login-left img {
            width: 100px;
            margin-bottom: 25px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.2));
        }

        .login-left h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .login-left .tagline {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        /* Kata-kata inspirasi */
        .quote-container {
            margin-top: 40px;
            max-width: 80%;
            border-top: 1px solid rgba(255,255,255,0.2);
            padding-top: 30px;
        }

        .quote-text {
            font-size: 1.1rem;
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 15px;
            font-weight: 400;
        }

        .quote-author {
            font-size: 0.85rem;
            opacity: 0.8;
            letter-spacing: 1px;
        }

        .quote-author i {
            margin-right: 8px;
        }

        .bulb-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        /* RIGHT SIDE */
        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-box h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2d2d3a;
            margin-bottom: 8px;
        }

        .login-box .subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            font-weight: 600;
            color: #4a4a5a;
            margin-bottom: 8px;
            display: block;
            font-size: 0.85rem;
        }

        .form-group .input-group-text {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-right: none;
            color: #6a1b9a;
        }

        .form-control {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #6a1b9a;
            box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, #6a1b9a, #9c27b0);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
            color: white;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(106, 27, 154, 0.3);
            background: linear-gradient(135deg, #5a158a, #8b24a0);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
        }

        .register-link a {
            color: #6a1b9a;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Alert */
        .alert-custom {
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }
            .login-right {
                width: 100%;
            }
            .login-box {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid login-container">
        <div class="row h-100">

            <!-- LEFT SIDE - UNGU DENGAN KATA-KATA PENEMU LAMPU -->
            <div class="col-md-6 login-left d-none d-md-flex">
                <div class="bulb-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <img src="../../template/assets/images/logo-lamp.png" alt="logo" onerror="this.src='https://placehold.co/100x100/ffffff/6a1b9a?text=L'">
                <h2>Peminjaman Lampu</h2>
                <p class="tagline">Solusi lengkap kebutuhan lampu untuk acara Anda</p>

                <div class="quote-container">
                    <div class="quote-text">
                        <i class="fas fa-quote-left me-2" style="opacity: 0.6;"></i> 
                        "Penemuan lampu bukan hanya tentang menghilangkan gelap, 
                        tetapi tentang memberikan harapan dan menerangi masa depan."
                    </div>
                    <div class="quote-author">
                        <i class="fas fa-user-circle"></i> — Thomas Alva Edison
                    </div>
                </div>

                <div class="quote-container">
                    <div class="quote-text">
                        <i class="fas fa-quote-left me-2" style="opacity: 0.6;"></i> 
                        "Cahaya adalah inspirasi terbesar umat manusia. 
                        Tanpa cahaya, tidak ada karya, tidak ada kemajuan."
                    </div>
                    <div class="quote-author">
                        <i class="fas fa-microscope"></i> — Nikola Tesla
                    </div>
                </div>

                <div class="quote-container">
                    <div class="quote-text">
                        <i class="fas fa-quote-left me-2" style="opacity: 0.6;"></i> 
                        "Dengan lampu yang tepat, setiap acara bisa menjadi 
                        momen yang tak terlupakan."
                    </div>
                    <div class="quote-author">
                        <i class="fas fa-lightbulb"></i> — Peminjaman Lampu
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE - FORM LOGIN -->
            <div class="col-md-6 login-right">
                <div class="login-box">

                    <div class="text-center d-md-none mb-4">
                        <i class="fas fa-lightbulb fa-3x" style="color: #6a1b9a;"></i>
                        <h3 class="mt-2">Peminjaman Lampu</h3>
                    </div>

                    <h3>Selamat Datang 👋</h3>
                    <p class="subtitle">Silakan masuk ke akun Anda untuk melanjutkan</p>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-custom">
                            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="proses_login.php">

                        <div class="form-group">
                            <label><i class="fas fa-user me-2"></i> Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-lock me-2"></i> Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </div>

                    </form>

                    <div class="register-link">
                        Belum punya akun? <a href="#">Hubungi Admin</a>
                    </div>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb"></i> Peminjaman Lampu | Sewa Lampu Event & Dekorasi
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>