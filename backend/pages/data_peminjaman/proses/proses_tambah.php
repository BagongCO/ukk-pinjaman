<?php
session_start();
include "../../../app.php";

// CEK APAKAH ADA PARAMETER ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID barang tidak ditemukan!");
}

$id = mysqli_real_escape_string($connect, $_GET['id']); // AMANKAN DARI SQL INJECTION

$data = mysqli_query($connect, "SELECT * FROM barang WHERE id_barang='$id'");
$row = mysqli_fetch_assoc($data);

// CEK APAKAH DATA DITEMUKAN
if (!$row) {
    die("Error: Barang dengan ID $id tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($row['nama_barang']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include "../../../partials/navbar.php"; // PERBAIKI PATH ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="../../../storage/barang/<?php echo htmlspecialchars($row['foto']); ?>" class="img-fluid" 
                     onerror="this.src='https://via.placeholder.com/500'">
            </div>

            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>

                <h4>
                    Rp <?php echo number_format((float)$row['harga_per_jam'], 0, ',', '.'); ?> / jam
                </h4>

                <?php if (isset($_SESSION['id_user'])) { ?>
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
                    <a href="../../../auth/login.php" class="btn btn-primary mt-3">
                        Login untuk menyewa
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include "../../../partials/footer.php"; // PERBAIKI PATH ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>