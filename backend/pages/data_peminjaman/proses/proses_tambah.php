<?php
session_start();
include "../../../app.php";

// Perbaiki: Gunakan 'id_barang' bukan 'id'
$id_barang = isset($_GET['id_barang']) ? $_GET['id_barang'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (!$id_barang) {
    echo "ID barang tidak ditemukan!";
    exit;
}

$data = mysqli_query($connect, "SELECT * FROM barang WHERE id_barang='$id_barang'");

if (!$data || mysqli_num_rows($data) == 0) {
    echo "Barang tidak ditemukan!";
    exit;
}

$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo htmlspecialchars($row['nama_barang']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php 
    // Perbaiki path include
    $navbar_path = __DIR__ . "/../../../partials/navbar.php";
    if (file_exists($navbar_path)) {
        include $navbar_path;
    } else {
        echo '<div class="alert alert-warning">Navbar tidak ditemukan</div>';
    }
    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($row['foto']) && file_exists("../storage/barang/" . $row['foto'])): ?>
                    <img src="../storage/barang/<?php echo htmlspecialchars($row['foto']); ?>" class="img-fluid">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400" class="img-fluid" alt="No Image">
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                
                <h4>
                    Rp <?php echo number_format($row['harga_per_jam'] ?? 0); ?> / jam
                </h4>

                <?php if (isset($_SESSION['id_user'])): ?>
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
                <?php else: ?>
                    <a href="../../auth/login.php" class="btn btn-primary mt-3">
                        Login untuk menyewa
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php 
    $footer_path = __DIR__ . "/../../../partials/footer.php";
    if (file_exists($footer_path)) {
        include $footer_path;
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>