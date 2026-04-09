<?php
include '../../app.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/index.php");
    exit;
}

// Ambil data peminjaman yang masih DIPINJAM
$query = "SELECT 
            p.*,
            b.nama_barang,
            b.harga_per_jam,
            u.username
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          LEFT JOIN users u ON p.id_user = u.id_user
          WHERE p.status = 'dipinjam'
          ORDER BY p.tanggal_pinjam DESC, p.jam_pinjam DESC";

$data = mysqli_query($connect, $query);
$totalPengembalian = mysqli_num_rows($data);
?>

<?php include '../../partials/header.php' ?>

<body>
    <div class="container-scroller">
        <?php include '../../partials/navbar.php' ?>
        <div class="container-fluid page-body-wrapper">
            <?php include '../../partials/sidebar.php' ?>
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-success text-white me-2">
                                <i class="mdi mdi-backup-restore"></i>
                            </span>
                            Pengembalian Barang
                        </h3>
                    </div>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title">
                                    <i class="mdi mdi-format-list-bulleted"></i> Daftar Peminjaman Aktif
                                </h4>
                                <a href="index.php" class="btn btn-gradient-primary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>

                            <div class="alert alert-info">
                                <i class="mdi mdi-information-outline"></i>
                                <strong>Total <?= $totalPengembalian ?> Peminjaman Aktif</strong> - Belum dikembalikan
                            </div>

                            <?php if ($totalPengembalian > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle" id="tablePengembalian">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>ID Pinjam</th>
                                                <th>Peminjam</th>
                                                <th>Barang</th>
                                                <th>Tgl Pinjam</th>
                                                <th>Jam Pinjam</th>
                                                <th>Durasi</th>
                                                <th>Total Harga</th>
                                                <th>Batas Kembali</th>
                                                <th>Telat</th>
                                                <th width="120">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                                <?php
                                                // Hitung keterlambatan
                                                $batas_kembali = $row['tanggal_kembali'] . ' ' . $row['jam_kembali'];
                                                $now = date('Y-m-d H:i:s');
                                                $telat = (strtotime($now) > strtotime($batas_kembali)) ? true : false;
                                                
                                                // Hitung denda (contoh: Rp 5000 per jam)
                                                $denda_per_jam = 5000;
                                                $jam_telat = 0;
                                                $denda = 0;
                                                if ($telat) {
                                                    $selisih = strtotime($now) - strtotime($batas_kembali);
                                                    $jam_telat = ceil($selisih / 3600);
                                                    $denda = $jam_telat * $denda_per_jam;
                                                }
                                                
                                                $telat_class = $telat ? 'text-danger fw-bold' : 'text-success';
                                                $telat_text = $telat ? "Telat {$jam_telat} jam (Denda Rp " . number_format($denda) . ")" : "Tepat waktu";
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['id_peminjaman'] ?></td>
                                                    <td><?= htmlspecialchars($row['username'] ?? 'User #'.$row['id_user']) ?></td>
                                                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                    <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                                    <td><?= $row['jam_pinjam'] ?></td>
                                                    <td><?= $row['durasi_jam'] ?> jam</td>
                                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                                    <td><?= date('d-m-Y', strtotime($row['tanggal_kembali'])) ?> <?= $row['jam_kembali'] ?></td>
                                                    <td><span class="<?= $telat_class ?>"><?= $telat_text ?></span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success w-100" onclick="pengembalian(<?= $row['id_peminjaman'] ?>, '<?= addslashes($row['nama_barang']) ?>', <?= $denda ?>)" title="Kembalikan">
                                                            <i class="mdi mdi-check"></i> Kembalikan
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="mdi mdi-backup-restore mdi-48px text-muted mb-3"></i>
                                    <h5>Tidak Ada Peminjaman Aktif</h5>
                                    <p class="text-muted">Semua barang sudah dikembalikan</p>
                                    <a href="index.php" class="btn btn-gradient-primary mt-2">Lihat Data Peminjaman</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php include '../../partials/footer.php'; ?>
            </div>
        </div>
    </div>

    <!-- ====================== MODAL PENGEMBALIAN ====================== -->
    <div class="modal fade" id="modalPengembalian" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-success text-white">
                    <h5 class="modal-title"><i class="mdi mdi-backup-restore"></i> Konfirmasi Pengembalian</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPengembalian" method="POST" action="../../action/pengembalian/pengembalian.php">
                    <div class="modal-body">
                        <input type="hidden" name="id_peminjaman" id="pengembalian_id">
                        <div class="text-center mb-3">
                            <i class="mdi mdi-package-variant mdi-48px text-success"></i>
                        </div>
                        <p class="text-center">Yakin akan mengembalikan barang <br> <b id="pengembalian_barang"></b> ?</p>
                        
                        <div class="alert alert-warning" id="denda_info" style="display: none;">
                            <i class="mdi mdi-alert"></i>
                            <strong>Perhatian!</strong><br>
                            Peminjaman ini mengalami keterlambatan.<br>
                            Denda: <b id="denda_nominal">Rp 0</b>
                        </div>
                        
                        <div class="mb-3">
                            <label>Kondisi Barang</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baik">Baik</option>
                                <option value="kurang_baik">Kurang Baik</option>
                                <option value="rusak">Rusak</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan kondisi barang atau lainnya..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tombol" class="btn btn-gradient-success">Konfirmasi Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../../partials/script.php' ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#tablePengembalian')) {
                $('#tablePengembalian').DataTable().destroy();
            }
            
            $('#tablePengembalian').DataTable({
                language: { 
                    search: "Cari:", 
                    lengthMenu: "Tampilkan _MENU_ data", 
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    emptyTable: "Tidak ada peminjaman aktif"
                },
                pageLength: 10,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 10] }
                ]
            });
        });

        function pengembalian(id, namaBarang, denda) {
            $('#pengembalian_id').val(id);
            $('#pengembalian_barang').text(namaBarang);
            
            if (denda > 0) {
                $('#denda_nominal').text('Rp ' + denda.toLocaleString('id-ID'));
                $('#denda_info').show();
            } else {
                $('#denda_info').hide();
            }
            
            $('#modalPengembalian').modal('show');
        }
    </script>

    <style>
        .table-responsive { overflow-x: auto; }
        .btn-close-white { filter: brightness(0) invert(1); }
    </style>

</body>
</html>