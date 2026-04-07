<?php
include "../backend/app.php";

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

                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#modalSewa">
                    Sewa Sekarang
                </button>

            </div>

        </div>

    </div>
    <!-- MODAL SEWA -->
    <div class="modal fade" id="modalSewa">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Sewa <?= htmlspecialchars($row['nama_barang']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="proses_pinjam.php">

                    <div class="modal-body">

                        <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
                        <input type="hidden" name="id_user" value="<?= $_SESSION['id_user'] ?>">

                        <div class="mb-3">
                            <label>Barang</label>
                            <input type="text" class="form-control" value="<?= $row['nama_barang'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Harga / Jam</label>
                            <input type="text" class="form-control" value="Rp <?= number_format($row['harga_per_jam']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Durasi Sewa (Jam)</label>
                            <input type="number" name="durasi_jam" class="form-control" min="1" required>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button class="btn btn-success">
                            Sewa Sekarang
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
    <?php include "partials/footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>