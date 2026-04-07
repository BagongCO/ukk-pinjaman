<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Register - Rental PS EON</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header text-center">
                        <h4>Register Peminjam</h4>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="register_proses.php">

                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-primary w-100">
                                Register
                            </button>

                        </form>

                        <div class="text-center mt-3">

                            Sudah punya akun?

                            <a href="login.php">
                                Login
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>