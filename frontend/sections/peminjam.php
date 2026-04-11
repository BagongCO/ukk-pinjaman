<?php
session_start();
include "../partials/header.php";
include "../partials/navbar.php";
include "../../config/connection.php";

// CEK LOGIN
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo "<script>alert('Silakan login dulu!'); window.location.href='login';</script>";
    exit;
}

// CEK ROLE
if ($_SESSION['role'] != 'peminjam') {
    echo "<script>alert('Halaman ini hanya untuk peminjam!'); window.location.href='./';</script>";
    exit;
}

$user_id = $_SESSION['id_user'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];

// PROSES TAMBAH PEMINJAMAN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_peminjaman'])) {
    $id_barang = (int)$_POST['id_barang'];
    $tanggal_pinjam = mysqli_real_escape_string($connect, $_POST['tanggal_pinjam']);
    $jam_pinjam = mysqli_real_escape_string($connect, $_POST['jam_pinjam']);
    $durasi_jam = (int)$_POST['durasi_jam'];
    
    $ambilBarang = mysqli_query($connect, "SELECT harga_per_jam, stok, nama_barang FROM barang WHERE id_barang = $id_barang");
    $barang = mysqli_fetch_assoc($ambilBarang);
    
    if (!$barang) {
        echo "<script>alert('Lampu tidak ditemukan!');</script>";
    } elseif ($barang['stok'] <= 0) {
        echo "<script>alert('Stok lampu habis!');</script>";
    } else {
        $total_harga = $barang['harga_per_jam'] * $durasi_jam;
        $waktu_kembali = date('Y-m-d H:i:s', strtotime("$tanggal_pinjam $jam_pinjam + $durasi_jam hours"));
        $tanggal_kembali = date('Y-m-d', strtotime($waktu_kembali));
        $jam_kembali = date('H:i:s', strtotime($waktu_kembali));
        
        $query = "INSERT INTO peminjaman (id_user, id_barang, tanggal_pinjam, jam_pinjam, tanggal_kembali, jam_kembali, durasi_jam, total_harga, status, created_at) 
                  VALUES ($user_id, $id_barang, '$tanggal_pinjam', '$jam_pinjam', '$tanggal_kembali', '$jam_kembali', $durasi_jam, $total_harga, 'dipinjam', NOW())";
        
        if (mysqli_query($connect, $query)) {
            mysqli_query($connect, "UPDATE barang SET stok = stok - 1 WHERE id_barang = $id_barang");
            echo "<script>alert('Peminjaman lampu berhasil! Total: Rp " . number_format($total_harga, 0, ',', '.') . "'); window.location.href='peminjam';</script>";
        } else {
            echo "<script>alert('Gagal: " . addslashes(mysqli_error($connect)) . "');</script>";
        }
    }
}

// AMBIL DATA PEMINJAMAN
$query_peminjaman = mysqli_query($connect, "SELECT p.*, b.nama_barang, b.harga_per_jam, u.nama as peminjam_nama
    FROM peminjaman p 
    LEFT JOIN barang b ON p.id_barang = b.id_barang 
    LEFT JOIN users u ON p.id_user = u.id_user
    WHERE p.id_user = $user_id 
    ORDER BY p.id_peminjaman DESC");

// HITUNG STATISTIK
$total_peminjaman = 0;
$dipinjam = 0;
$dikembalikan = 0;
$batal = 0;

$data_peminjaman = [];
while ($row = mysqli_fetch_assoc($query_peminjaman)) {
    $data_peminjaman[] = $row;
    $total_peminjaman++;
    if ($row['status'] == 'dipinjam') $dipinjam++;
    elseif ($row['status'] == 'dikembalikan') $dikembalikan++;
    elseif ($row['status'] == 'batal') $batal++;
}

// AMBIL DAFTAR LAMPU UNTUK DROPDOWN
$query_lampu = mysqli_query($connect, "SELECT id_barang, nama_barang, harga_per_jam, stok FROM barang WHERE stok > 0 ORDER BY nama_barang");
?>

<div class="container mt-4">
    <!-- Header Compact -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus-circle"></i> Pinjam Baru
        </button>
    </div>
    
    <!-- Statistik Cards Compact -->
    <div class="row g-2 mb-4">
        <div class="col-3">
            <div class="card bg-gradient-purple text-white text-center p-2 border-0">
                <h3 class="mb-0"><?= $total_peminjaman ?></h3>
                <small>Total</small>
            </div>
        </div>
        <div class="col-3">
            <div class="card bg-warning text-white text-center p-2 border-0">
                <h3 class="mb-0"><?= $dipinjam ?></h3>
                <small>Dipinjam</small>
            </div>
        </div>
        <div class="col-3">
            <div class="card bg-success text-white text-center p-2 border-0">
                <h3 class="mb-0"><?= $dikembalikan ?></h3>
                <small>Kembali</small>
            </div>
        </div>
        <div class="col-3">
            <div class="card bg-danger text-white text-center p-2 border-0">
                <h3 class="mb-0"><?= $batal ?></h3>
                <small>Batal</small>
            </div>
        </div>
    </div>
    
    <!-- Card Peminjaman Compact -->
    <div class="row g-3">
        <?php if (count($data_peminjaman) > 0): ?>
            <?php foreach ($data_peminjaman as $row): 
                $statusColor = $row['status'] == 'dipinjam' ? 'warning' : ($row['status'] == 'dikembalikan' ? 'success' : 'danger');
                $statusIcon = $row['status'] == 'dipinjam' ? 'fa-clock' : ($row['status'] == 'dikembalikan' ? 'fa-check-circle' : 'fa-times-circle');
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-3">
                            <!-- Header Card -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0 fw-bold text-purple"><?= htmlspecialchars($row['nama_barang']) ?></h6>
                                    <small class="text-muted">Rp <?= number_format($row['harga_per_jam'], 0, ',', '.') ?>/jam</small>
                                </div>
                                <span class="badge bg-<?= $statusColor ?> bg-opacity-10 text-<?= $statusColor ?> border border-<?= $statusColor ?> border-opacity-25 px-2 py-1">
                                    <i class="fas <?= $statusIcon ?> me-1"></i> <?= ucfirst($row['status']) ?>
                                </span>
                            </div>
                            
                            <!-- Info Pinjam -->
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">📅 Pinjam</small>
                                        <small class="fw-bold"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></small>
                                        <small class="text-muted"> <?= $row['jam_pinjam'] ?></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded p-2">
                                        <small class="text-muted d-block">📅 Kembali</small>
                                        <small class="fw-bold"><?= $row['tanggal_kembali'] ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-' ?></small>
                                        <small class="text-muted"> <?= $row['jam_kembali'] ?? '-' ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Durasi & Total -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <i class="fas fa-hourglass-half text-purple"></i>
                                    <small class="fw-bold"><?= $row['durasi_jam'] ?> jam</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Total</small><br>
                                    <span class="fw-bold text-purple">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            
                            <!-- Tombol Aksi -->
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-purple flex-grow-1" onclick="detailPeminjaman(<?= htmlspecialchars(json_encode($row)) ?>)">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                <?php if ($row['status'] == 'dipinjam'): ?>
                                    <button class="btn btn-sm btn-outline-purple flex-grow-1" onclick="cetakStruk(<?= htmlspecialchars(json_encode($row)) ?>)">
                                        <i class="fas fa-print"></i> Struk
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-lightbulb fa-3x text-muted mb-3"></i>
                    <h5>Belum Ada Peminjaman</h5>
                    <p class="text-muted">Klik tombol "Pinjam Baru" untuk meminjam lampu</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL TAMBAH PEMINJAMAN -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient-purple text-white border-0 rounded-top-4">
                <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Form Peminjaman Lampu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Peminjam</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($nama) ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Lampu <span class="text-danger">*</span></label>
                        <select name="id_barang" id="pilih_lampu" class="form-select" required>
                            <option value="">-- Pilih Lampu --</option>
                            <?php while ($lampu = mysqli_fetch_assoc($query_lampu)): ?>
                                <option value="<?= $lampu['id_barang'] ?>" 
                                    data-harga="<?= $lampu['harga_per_jam'] ?>"
                                    data-stok="<?= $lampu['stok'] ?>">
                                    <?= htmlspecialchars($lampu['nama_barang']) ?> - Rp <?= number_format($lampu['harga_per_jam']) ?>/jam (Stok: <?= $lampu['stok'] ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Tanggal Pinjam</label>
                            <input type="date" name="tanggal_pinjam" id="tgl_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Jam Pinjam</label>
                            <input type="time" name="jam_pinjam" id="jam_pinjam" class="form-control" value="<?= date('H:i') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Durasi Sewa (Jam)</label>
                        <input type="number" name="durasi_jam" id="durasi" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="alert alert-purple mb-0">
                        <small><strong>Preview:</strong><br>
                        📅 Kembali: <span id="preview_tgl_kembali">-</span> <span id="preview_jam_kembali">-</span><br>
                        💰 Total: Rp <span id="preview_total">0</span></small>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_peminjaman" class="btn btn-purple">Pinjam Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DETAIL -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient-purple text-white border-0 rounded-top-4">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Detail Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="detailContent">
                <div class="text-center">Loading...</div>
            </div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL STRUK -->
<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient-purple text-white border-0 rounded-top-4">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Struk Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="strukContent"></div>
            <div class="modal-footer border-0 pb-4 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-purple" onclick="printStruk()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<script>
// Preview tanggal kembali
function updatePreview() {
    let tglPinjam = document.getElementById('tgl_pinjam').value;
    let jamPinjam = document.getElementById('jam_pinjam').value;
    let durasi = parseInt(document.getElementById('durasi').value) || 0;
    
    if (tglPinjam && jamPinjam && durasi > 0) {
        let date = new Date(tglPinjam + 'T' + jamPinjam);
        date.setHours(date.getHours() + durasi);
        document.getElementById('preview_tgl_kembali').innerText = date.toLocaleDateString('id-ID');
        document.getElementById('preview_jam_kembali').innerText = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    } else {
        document.getElementById('preview_tgl_kembali').innerText = '-';
        document.getElementById('preview_jam_kembali').innerText = '-';
    }
}

// Hitung total harga
function hitungTotal() {
    let select = document.getElementById('pilih_lampu');
    let harga = select.options[select.selectedIndex]?.getAttribute('data-harga') || 0;
    let durasi = parseInt(document.getElementById('durasi').value) || 0;
    let total = harga * durasi;
    document.getElementById('preview_total').innerText = total.toLocaleString('id-ID');
    updatePreview();
}

document.getElementById('pilih_lampu')?.addEventListener('change', hitungTotal);
document.getElementById('durasi')?.addEventListener('input', hitungTotal);
document.getElementById('tgl_pinjam')?.addEventListener('change', updatePreview);
document.getElementById('jam_pinjam')?.addEventListener('change', updatePreview);
hitungTotal();

// Detail Peminjaman
function detailPeminjaman(data) {
    let modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    let html = `
        <div class="mb-3">
            <small class="text-muted">ID Peminjaman</small>
            <div class="fw-bold">#${data.id_peminjaman}</div>
        </div>
        <div class="mb-3">
            <small class="text-muted">Peminjam</small>
            <div class="fw-bold">${data.peminjam_nama}</div>
        </div>
        <div class="mb-3">
            <small class="text-muted">Lampu</small>
            <div class="fw-bold">${data.nama_barang}</div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <small class="text-muted">Tanggal Pinjam</small>
                <div class="fw-bold">${new Date(data.tanggal_pinjam).toLocaleDateString('id-ID')}</div>
                <small>${data.jam_pinjam}</small>
            </div>
            <div class="col-6 mb-3">
                <small class="text-muted">Tanggal Kembali</small>
                <div class="fw-bold">${data.tanggal_kembali ? new Date(data.tanggal_kembali).toLocaleDateString('id-ID') : '-'}</div>
                <small>${data.jam_kembali || '-'}</small>
            </div>
        </div>
        <div class="mb-3">
            <small class="text-muted">Durasi & Total</small>
            <div class="fw-bold">${data.durasi_jam} jam = Rp ${parseInt(data.total_harga).toLocaleString('id-ID')}</div>
        </div>
        <div class="mb-3">
            <small class="text-muted">Status</small>
            <div><span class="badge bg-${data.status == 'dipinjam' ? 'warning' : (data.status == 'dikembalikan' ? 'success' : 'danger')}">${data.status}</span></div>
        </div>
    `;
    document.getElementById('detailContent').innerHTML = html;
    modal.show();
}

// Cetak Struk
function cetakStruk(data) {
    let modal = new bootstrap.Modal(document.getElementById('modalStruk'));
    let html = `
        <div class="struk-wrapper" id="strukPrint">
            <div class="text-center p-4">
                <h4 class="text-purple">PEMINJAMAN LAMPU</h4>
                <p class="mb-0">Jl. Contoh No. 123</p>
                <small>Telp: (021) 1234567</small>
                <hr>
                <p><strong>#${data.id_peminjaman}</strong><br>
                <small>${new Date().toLocaleDateString('id-ID')} ${new Date().toLocaleTimeString('id-ID')}</small></p>
                <hr>
                <table style="width:100%">
                    <tr><td>Peminjam</td><td class="text-end">: ${data.peminjam_nama}</td></tr>
                    <tr><td>Barang</td><td class="text-end">: ${data.nama_barang}</td></tr>
                    <tr><td>Tgl Pinjam</td><td class="text-end">: ${new Date(data.tanggal_pinjam).toLocaleDateString('id-ID')} ${data.jam_pinjam}</td></tr>
                    <tr><td>Tgl Kembali</td><td class="text-end">: ${data.tanggal_kembali ? new Date(data.tanggal_kembali).toLocaleDateString('id-ID') : '-'} ${data.jam_kembali || '-'}</td></tr>
                    <tr><td>Durasi</td><td class="text-end">: ${data.durasi_jam} jam</td></tr>
                    <tr><td>Harga/jam</td><td class="text-end">: Rp ${parseInt(data.harga_per_jam).toLocaleString('id-ID')}</td></tr>
                    <tr style="border-top:1px solid #000"><td><strong>TOTAL</strong></td><td class="text-end"><strong>: Rp ${parseInt(data.total_harga).toLocaleString('id-ID')}</strong></td></tr>
                    <tr><td>Status</td><td class="text-end">: ${data.status}</td></tr>
                </table>
                <hr>
                <small>Terima kasih<br>Barang wajib dikembalikan tepat waktu</small>
            </div>
        </div>
    `;
    document.getElementById('strukContent').innerHTML = html;
    modal.show();
}

function printStruk() {
    let printContents = document.getElementById('strukPrint').innerHTML;
    let originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>

<style>
     /* Tambahan margin top untuk halaman peminjaman */
    .container.mt-4 {
        margin-top: 140px !important;
    }
    
    .bg-gradient-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .btn-purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
    }
    .btn-purple:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        color: white;
    }
    .btn-outline-purple {
        border: 1px solid #764ba2;
        color: #764ba2;
        background: transparent;
    }
    .btn-outline-purple:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    .text-purple {
        color: #764ba2;
    }
    .alert-purple {
        background: #f3e8ff;
        border: 1px solid #d8b4fe;
        color: #6b21a5;
        border-radius: 12px;
    }
    .bg-opacity-10 {
        --bs-bg-opacity: 0.1;
    }
    .rounded-4 {
        border-radius: 1rem;
    }
    .struk-wrapper {
        font-family: 'Courier New', monospace;
    }
    @media print {
        body * { visibility: hidden; }
        .struk-wrapper, .struk-wrapper * { visibility: visible; }
        .struk-wrapper { position: absolute; top: 0; left: 0; width: 100%; }
    }
</style>

<?php include "../partials/footer.php"; ?>
<?php include "../partials/script.php"; ?>