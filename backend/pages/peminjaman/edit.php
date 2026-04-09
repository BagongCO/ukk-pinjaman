<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../partials/header.php';
$page = 'peminjaman';
include '../../partials/sidebar.php';

include '../../action/peminjaman/show.php';
include '../../app.php';

$queryUser = mysqli_query($connect, "SELECT id_user, username, nama_lengkap FROM users ORDER BY nama_lengkap");
$queryBarang = mysqli_query($connect, "SELECT id_barang, nama_barang, harga_per_jam FROM barang ORDER BY nama_barang");
?>

<style>
    body, #main, .container-fluid, .page-body-wrapper { background-color: #f8f9fc !important; }
    #main {
        margin-left: 260px;
        margin-top: 70px;
        padding: 20px;
        width: calc(100% - 260px);
        background-color: #f8f9fc !important;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        border: 1px solid #e3e6f0 !important;
        background-color: #ffffff !important;
    }
    .card-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #e3e6f0 !important;
        padding: 20px 25px !important;
    }
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #4e73df;
    }
    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border: 1px solid #d1d3e2 !important;
        border-radius: 0.35rem !important;
        padding: 0.75rem 1rem !important;
    }
    @media (max-width: 768px) { #main { margin-left: 0; width: 100%; } }
</style>

<div class="container-fluid page-body-wrapper">
    <div id="main">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title">
                        <i class="fas fa-edit"></i> Edit Peminjaman
                    </h4>
                    <a href="./index.php" class="btn btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <form action="../../action/peminjaman/update.php?id=<?= $peminjaman->id_peminjaman ?>" method="POST">
                            
                            <div class="mb-4">
                                <label for="id_user" class="form-label">Peminjam *</label>
                                <select name="id_user" id="id_user" class="form-select" required>
                                    <option value="">-- Pilih Peminjam --</option>
                                    <?php while($user = mysqli_fetch_assoc($queryUser)): ?>
                                        <option value="<?= $user['id_user'] ?>" <?= ($user['id_user'] == $peminjaman->id_user) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user['nama_lengkap'] ?? $user['username']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="id_barang" class="form-label">Barang *</label>
                                <select name="id_barang" id="id_barang" class="form-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php while($barang = mysqli_fetch_assoc($queryBarang)): ?>
                                        <option value="<?= $barang['id_barang'] ?>" 
                                                data-harga="<?= $barang['harga_per_jam'] ?>"
                                                <?= ($barang['id_barang'] == $peminjaman->id_barang) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($barang['nama_barang']) ?> - Rp <?= number_format($barang['harga_per_jam']) ?>/jam
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="<?= $peminjaman->tanggal_pinjam ?>" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="jam_pinjam" class="form-label">Jam Pinjam *</label>
                                    <input type="time" name="jam_pinjam" id="jam_pinjam" class="form-control" value="<?= $peminjaman->jam_pinjam ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="durasi_jam" class="form-label">Durasi Sewa (Jam) *</label>
                                <input type="number" name="durasi_jam" id="durasi_jam" class="form-control" min="1" value="<?= $peminjaman->durasi_jam ?>" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="form-label">Status *</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="dipinjam" <?= ($peminjaman->status == 'dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                                    <option value="dikembalikan" <?= ($peminjaman->status == 'dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
                                    <option value="batal" <?= ($peminjaman->status == 'batal') ? 'selected' : '' ?>>Batal</option>
                                </select>
                            </div>
                            
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Informasi:</h6>
                                <hr>
                                <div><strong>Tanggal Kembali:</strong> <span id="preview_tanggal_kembali">-</span></div>
                                <div><strong>Jam Kembali:</strong> <span id="preview_jam_kembali">-</span></div>
                                <div><strong>Total Harga:</strong> <span id="preview_total_harga">Rp 0</span></div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="./index.php" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" name="tombol" class="btn btn-success">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../../partials/footer.php'; ?>
    </div>
</div>

<?php include '../../partials/script.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
const hargaAwal = <?= $peminjaman->harga_per_jam ?? 0 ?>;
const durasiAwal = <?= $peminjaman->durasi_jam ?? 0 ?>;
const barangSelect = document.getElementById('id_barang');
const durasiInput = document.getElementById('durasi_jam');
const tanggalPinjam = document.getElementById('tanggal_pinjam');
const jamPinjam = document.getElementById('jam_pinjam');
const previewTglKembali = document.getElementById('preview_tanggal_kembali');
const previewJamKembali = document.getElementById('preview_jam_kembali');
const previewTotalHarga = document.getElementById('preview_total_harga');

function updatePreview() {
    const selectedOption = barangSelect.options[barangSelect.selectedIndex];
    let hargaPerJam = selectedOption.getAttribute('data-harga');
    if (!hargaPerJam && hargaAwal) hargaPerJam = hargaAwal;
    const durasi = parseInt(durasiInput.value) || 0;
    
    if (hargaPerJam && durasi > 0) {
        const total = parseInt(hargaPerJam) * durasi;
        previewTotalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
    } else {
        previewTotalHarga.textContent = 'Rp 0';
    }
    
    if (tanggalPinjam.value && jamPinjam.value && durasi > 0) {
        const tanggalKembali = new Date(tanggalPinjam.value + 'T' + jamPinjam.value);
        tanggalKembali.setHours(tanggalKembali.getHours() + durasi);
        previewTglKembali.textContent = tanggalKembali.toLocaleDateString('id-ID');
        previewJamKembali.textContent = tanggalKembali.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    } else {
        previewTglKembali.textContent = '-';
        previewJamKembali.textContent = '-';
    }
}

barangSelect.addEventListener('change', updatePreview);
durasiInput.addEventListener('input', updatePreview);
tanggalPinjam.addEventListener('change', updatePreview);
jamPinjam.addEventListener('input', updatePreview);
updatePreview();
</script>