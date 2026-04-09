<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../partials/header.php";
$page = 'peminjaman';
include "../../partials/sidebar.php";

include '../../action/peminjaman/show.php';
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
    .info-card {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #4e73df;
    }
    .info-label {
        font-size: 0.9rem;
        color: #858796;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .info-value {
        font-size: 1.1rem;
        color: #5a5c69;
        font-weight: 500;
    }
    .badge-dipinjam { background: #f6c23e; color: white; padding: 8px 20px; border-radius: 20px; }
    .badge-dikembalikan { background: #1cc88a; color: white; padding: 8px 20px; border-radius: 20px; }
    .badge-batal { background: #e74a3b; color: white; padding: 8px 20px; border-radius: 20px; }
    @media (max-width: 768px) { #main { margin-left: 0; width: 100%; } }
</style>

<div class="container-fluid page-body-wrapper">
    <div id="main">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title">
                        <i class="fas fa-info-circle"></i> Detail Peminjaman
                    </h4>
                    <a href="./index.php" class="btn btn-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="info-card">
                            <div class="info-label">ID Peminjaman</div>
                            <div class="info-value">#<?= htmlspecialchars($peminjaman->id_peminjaman ?? '') ?></div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Peminjam</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman->nama_lengkap ?? $peminjaman->username ?? '-') ?></div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Barang</div>
                            <div class="info-value"><?= htmlspecialchars($peminjaman->nama_barang ?? '-') ?> (Rp <?= number_format($peminjaman->harga_per_jam ?? 0) ?>/jam)</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Tanggal Pinjam</div>
                                    <div class="info-value"><?= date('d-m-Y', strtotime($peminjaman->tanggal_pinjam)) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Jam Pinjam</div>
                                    <div class="info-value"><?= $peminjaman->jam_pinjam ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Tanggal Kembali</div>
                                    <div class="info-value"><?= date('d-m-Y', strtotime($peminjaman->tanggal_kembali)) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Jam Kembali</div>
                                    <div class="info-value"><?= $peminjaman->jam_kembali ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Durasi Sewa</div>
                                    <div class="info-value"><?= $peminjaman->durasi_jam ?> Jam</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-label">Total Harga</div>
                                    <div class="info-value">Rp <?= number_format($peminjaman->total_harga, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="badge-<?= $peminjaman->status ?>">
                                    <?= ucfirst($peminjaman->status) ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Tanggal Dibuat</div>
                            <div class="info-value"><?= date('d-m-Y H:i:s', strtotime($peminjaman->created_at)) ?></div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="./index.php" class="btn btn-outline-secondary">Kembali</a>
                            <div>
                                <a href="./edit.php?id=<?= $peminjaman->id_peminjaman ?>" class="btn btn-warning">Edit</a>
                                <a href="../../action/peminjaman/destroy.php?id=<?= $peminjaman->id_peminjaman ?>" 
                                   onclick="return confirm('Yakin hapus?')" class="btn btn-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include '../../partials/footer.php'; ?>
    </div>
</div>

<?php include '../../partials/script.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">