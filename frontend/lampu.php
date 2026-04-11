<!-- LIST LAMPU -->
<section class="py-5" id="lampu">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2><i class="fas fa-lightbulb me-2"></i> Lampu Tersedia</h2>
            <p>Pilihan terbaik untuk kebutuhan cahaya anda</p>
        </div>
        <div class="row g-4">
            <?php if (mysqli_num_rows($query_lampu) > 0): ?>
                <?php $delay = 100; while ($l = mysqli_fetch_assoc($query_lampu)): ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                        <div class="card card-lampu">
                            <div class="card-badge"><i class="fas fa-star me-1"></i> Terbaru</div>
                            <img src="<?= (!empty($l['foto']) && file_exists('backend/storage/barang/' . $l['foto'])) ? 'backend/storage/barang/' . $l['foto'] : 'https://placehold.co/400x250/7b2cbf/ffffff?text=' . urlencode($l['nama_barang']) ?>" class="card-img-top" alt="<?= htmlspecialchars($l['nama_barang']) ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?= htmlspecialchars($l['nama_barang']) ?></h5>
                                    <span class="stok-badge <?= $l['stok'] <= 0 ? 'habis' : '' ?>">
                                        <i class="fas fa-boxes me-1"></i> stok <?= $l['stok'] ?>
                                    </span>
                                </div>
                                <div class="price mt-2">Rp <?= number_format($l['harga_per_jam'], 0, ',', '.') ?> <small>/ jam</small></div>
                                <p class="text-muted small mt-2"><?= substr(htmlspecialchars($l['deskripsi'] ?? 'Lampu berkualitas untuk berbagai acara'), 0, 70) ?>...</p>
                                <a href="peminjam" class="btn btn-card mt-3"><i class="fas fa-calendar-check me-2"></i> Sewa Sekarang</a>
                            </div>
                        </div>
                    </div>
                <?php $delay += 100; endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-lightbulb fa-4x text-muted mb-3"></i>
                    <h5 class="mt-3">Belum ada lampu tersedia</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- STATISTIK -->
<div class="container">
    <div class="stats-wrapper" data-aos="zoom-in">
        <div class="row text-center">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-lightbulb"></i></div>
                    <div class="stat-number"><?= $total_lampu ?></div>
                    <div class="stat-label">Total Lampu</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-tags"></i></div>
                    <div class="stat-number"><?= $total_jenis ?></div>
                    <div class="stat-label">Jenis Lampu</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-3 mt-md-0">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-number"><?= $total_sewa ?></div>
                    <div class="stat-label">Sewa Sukses</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mt-3 mt-md-0">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-number"><?= $total_dipinjam ?></div>
                    <div class="stat-label">Sedang Dipinjam</div>
                </div>
            </div>
        </div>
    </div>
</div>