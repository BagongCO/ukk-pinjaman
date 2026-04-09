<?php
session_start();
include "../../../app.php";

$id = $_GET['id'];

$data = mysqli_query($connect, "SELECT * FROM barang WHERE id_barang='$id'");
$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>

<head>

    <title><?php echo $row['nama_barang']; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php include "partials/navbar.php"; ?>

    <div class="container mt-5">

        <div class="row">

            <div class="col-md-6">

                <img src="../storage/barang/<?php echo $row['foto']; ?>" class="img-fluid">

            </div>

            <div class="col-md-6">

                <h2><?php echo $row['nama_barang']; ?></h2>

                <p><?php echo $row['deskripsi']; ?></p>

                <h4>
                    Rp <?php echo number_format($row['harga_per_jam']); ?> / jam
                </h4>

                <?php if (isset($_SESSION['id_user'])) { ?>

                    <!-- FORM PINJAM -->

                    <form action="proses_pinjam.php" method="POST">

                        <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">

                        <div class="mt-3">

                            <label>Durasi Sewa (Jam)</label>

                            <input type="number" name="durasi_jam" class="form-control" required min="1">

                        </div>

                        <button class="btn btn-success mt-3">
                            Sewa Sekarang
                        </button>

                    </form>

                <?php } else { ?>

                    <a href="auth/login.php" class="btn btn-primary mt-3">
                        Login untuk menyewa
                    </a>

                <?php } ?>

            </div>

        </div>

    </div>

    <?php include "partials/footer.php"; ?>

</body>

</html>