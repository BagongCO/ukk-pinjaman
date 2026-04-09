<?php
session_start();
include "../../../app.php";

// Debug: Lihat semua parameter yang diterima
// echo "<pre>";
// print_r($_GET);
// print_r($_POST);
// echo "</pre>";
// exit;

// Cek parameter dari berbagai kemungkinan
$id_barang = null;

if (isset($_GET['id_barang']) && !empty($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_barang = $_GET['id'];
} elseif (isset($_POST['id_barang']) && !empty($_POST['id_barang'])) {
    $id_barang = $_POST['id_barang'];
} elseif (isset($_POST['id']) && !empty($_POST['id'])) {
    $id_barang = $_POST['id'];
}

// Jika masih tidak ada, tampilkan pesan error yang lebih jelas
if (!$id_barang) {
    echo "<div style='padding: 20px; font-family: Arial;'>";
    echo "<h3>Error: ID Barang tidak ditemukan!</h3>";
    echo "<p>Parameter yang diterima:</p>";
    echo "<pre>";
    echo "GET: ";
    print_r($_GET);
    echo "\nPOST: ";
    print_r($_POST);
    echo "</pre>";
    echo "<p>Kembali ke <a href='../index.php'>halaman utama</a></p>";
    echo "</div>";
    exit;
}

// Validasi ID barang
$id_barang = intval($id_barang);

if ($id_barang <= 0) {
    echo "ID barang tidak valid!";
    exit;
}

$data = mysqli_query($connect, "SELECT * FROM barang WHERE id_barang='$id_barang'");

if (!$data || mysqli_num_rows($data) == 0) {
    echo "Barang dengan ID $id_barang tidak ditemukan di database!";
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
    // Cari navbar di beberapa lokasi
    $navbar_paths = [
        __DIR__ . "/../../../partials/navbar.php",
        __DIR__ . "/../../partials/navbar.php", 
        __DIR__ . "/../partials/navbar.php",
        __DIR__ . "/partials/navbar.php"
    ];
    
    $navbar_found = false;
    foreach ($navbar_paths as $path) {
        if (file_exists($path)) {
            include $path;
            $navbar_found = true;
            break;
        }
    }
    
    if (!$navbar_found) {
        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container">
                    <a class="navbar-brand" href="#">Aplikasi Peminjaman</a>
                </div>
              </nav>';
    }
    ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <?php 
                $foto_path = "../storage/barang/" . $row['foto'];
                if (!empty($row['foto']) && file_exists($foto_path)): ?>
                    <img src="<?php echo $foto_path; ?>" class="img-fluid" style="max-height: 400px; width: 100%; object-fit: cover;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x300?text=No+Image" class="img-fluid">
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($row['nama_barang']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($row['deskripsi'])); ?></p>
                
                <h4 class="text-primary">
                    Rp <?php echo number_format($row['harga_per_jam'] ?? 0); ?> / jam
                </h4>

                <?php if (isset($_SESSION['id_user'])): ?>
                    <form action="proses_pinjam.php" method="POST" class="mt-4">
                        <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>">
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">

                        <div class="mb-3">
                            <label for="durasi_jam" class="form-label">Durasi Sewa (Jam)</label>
                            <input type="number" name="durasi_jam" id="durasi_jam" class="form-control" required min="1" value="1">
                            <div class="form-text">Minimal 1 jam</div>
                        </div>

                        <div class="mb-3">
                            <label>Total Harga:</label>
                            <h5 id="total_harga" class="text-success">Rp <?php echo number_format($row['harga_per_jam']); ?></h5>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Sewa Sekarang
                        </button>
                        <a href="../index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        Silakan <a href="../../auth/login.php">login</a> untuk menyewa barang
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // Hitung total harga otomatis
    const hargaPerJam = <?php echo $row['harga_per_jam']; ?>;
    const durasiInput = document.getElementById('durasi_jam');
    const totalHargaSpan = document.getElementById('total_harga');
    
    if (durasiInput) {
        durasiInput.addEventListener('input', function() {
            const durasi = parseInt(this.value) || 0;
            const total = hargaPerJam * durasi;
            totalHargaSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
        });
    }
    </script>

    <?php 
    $footer_paths = [
        __DIR__ . "/../../../partials/footer.php",
        __DIR__ . "/../../partials/footer.php",
        __DIR__ . "/../partials/footer.php", 
        __DIR__ . "/partials/footer.php"
    ];
    
    $footer_found = false;
    foreach ($footer_paths as $path) {
        if (file_exists($path)) {
            include $path;
            $footer_found = true;
            break;
        }
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>