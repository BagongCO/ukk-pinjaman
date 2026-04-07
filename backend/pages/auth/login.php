<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Rental PS EON</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../template/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../template/assets/css/style.css">
    <link rel="shortcut icon" href="../../template/assets/images/faviconps.png" />
    <style>
        body {
            background: #f4f6f9;
        }

        .login-container {
            height: 100vh;
        }

        .login-left {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 40px;
        }

        .login-left img {
            width: 120px;
            margin-bottom: 20px;
        }

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .btn-login {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
        }

        .btn-login:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div class="container-fluid login-container">
        <div class="row h-100">

            <!-- LEFT SIDE -->
            <div class="col-md-6 login-left d-none d-md-flex">
                <img src="../../template/assets/images/logo.svg" alt="logo">
                <h2>Rental PS EON</h2>
                <p>Tempat terbaik untuk bermain dan rental PlayStation 🎮</p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-6 login-right">
                <div class="login-box">

                    <h3 class="mb-3">Login</h3>
                    <p class="text-muted">Silakan masuk ke akun Anda</p>

                    <form method="POST" action="proses_login.php">

                        <div class="form-group mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-login text-white">
                                Login
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</body>

</html>