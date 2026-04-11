<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Peminjaman Lampu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* LEFT SIDE - QUOTES */
        .auth-left {
            flex: 1;
            background: linear-gradient(135deg, #4a148c, #6a1b9a, #9c27b0);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: "💡";
            font-size: 200px;
            position: absolute;
            bottom: -30px;
            right: -30px;
            opacity: 0.08;
            pointer-events: none;
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
        }

        .logo-icon i {
            font-size: 35px;
        }

        .auth-left h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .auth-left p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 40px;
        }

        .quote-item {
            margin-bottom: 30px;
            border-left: 3px solid rgba(255,255,255,0.3);
            padding-left: 20px;
        }

        .quote-text {
            font-size: 0.9rem;
            line-height: 1.5;
            font-style: italic;
            margin-bottom: 8px;
        }

        .quote-author {
            font-size: 0.75rem;
            opacity: 0.7;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* RIGHT SIDE - FORM */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            background: white;
        }

        .auth-form {
            width: 100%;
            max-width: 320px;
        }

        .auth-form h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .auth-form .subtitle {
            color: #6c757d;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group label i {
            margin-right: 5px;
            color: #6a1b9a;
        }

        .input-group {
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: #6a1b9a;
            box-shadow: 0 0 0 3px rgba(106, 27, 154, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            padding: 0 0 0 12px;
            color: #6a1b9a;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: none;
            font-size: 0.85rem;
            background: transparent;
            border-radius: 10px;
        }

        .form-control:focus {
            outline: none;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #6a1b9a, #9c27b0);
            border: none;
            border-radius: 10px;
            padding: 11px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(106, 27, 154, 0.3);
        }

        .alert {
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 3px solid #10b981;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8rem;
        }

        .login-link a {
            color: #6a1b9a;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 800px) {
            .auth-container {
                flex-direction: column;
                max-width: 400px;
            }
            .auth-left {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- LEFT SIDE - QUOTES -->
        <div class="auth-left">
            <div class="logo-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <h1>Peminjaman Lampu</h1>
            <p>Daftar sebagai Peminjam</p>

            <div class="quote-item">
                <div class="quote-text">
                    <i class="fas fa-quote-left" style="font-size: 0.7rem; opacity: 0.5; margin-right: 5px;"></i>
                    Penemuan lampu bukan hanya tentang menghilangkan gelap, tetapi tentang memberikan harapan dan menerangi masa depan.
                </div>
                <div class="quote-author">
                    <i class="fas fa-user-circle"></i> — Thomas Alva Edison
                </div>
            </div>

            <div class="quote-item">
                <div class="quote-text">
                    <i class="fas fa-quote-left" style="font-size: 0.7rem; opacity: 0.5; margin-right: 5px;"></i>
                    Cahaya adalah inspirasi terbesar umat manusia. Tanpa cahaya, tidak ada karya, tidak ada kemajuan.
                </div>
                <div class="quote-author">
                    <i class="fas fa-microscope"></i> — Nikola Tesla
                </div>
            </div>

            <div class="quote-item">
                <div class="quote-text">
                    <i class="fas fa-quote-left" style="font-size: 0.7rem; opacity: 0.5; margin-right: 5px;"></i>
                    Dengan lampu yang tepat, setiap acara bisa menjadi momen yang tak terlupakan.
                </div>
                <div class="quote-author">
                    <i class="fas fa-lightbulb"></i> — Peminjaman Lampu
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - FORM REGISTER -->
        <div class="auth-right">
            <div class="auth-form">
                <h2>Daftar Akun 🎉</h2>
                <p class="subtitle">Isi data diri untuk menjadi Peminjam</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="auth/register">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Lengkap *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user-circle"></i> Username *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Konfirmasi Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <!-- Role otomatis peminjam (id_role = 3) -->
                    <input type="hidden" name="id_role" value="3">

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>
                </form>

                <div class="login-link">
                    Sudah punya akun? <a href="login">Login di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');

        function validatePassword() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Password tidak sama!");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    </script>
</body>
</html>