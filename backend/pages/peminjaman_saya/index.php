<?php
include '../../app.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/index.php");
    exit;
}

// Hanya role peminjam (id_role = 3) yang bisa akses
if ($_SESSION['role'] != 'peminjam') {
    header("Location: ../dashboard/index.php");
    exit;
}

$id_user = $_SESSION['id_user'];

// Ambil data peminjaman milik user ini
$query = "SELECT 
            p.*,
            b.nama_barang,
            b.harga_per_jam
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          WHERE p.id_user = $id_user
          ORDER BY p.id_peminjaman DESC";

$data = mysqli_query($connect, $query);
$total = mysqli_num_rows($data);

// Ambil daftar barang untuk dropdown modal tambah (stok > 0)
$barang = mysqli_query($connect, "SELECT id_barang, nama_barang, harga_per_jam, stok FROM barang WHERE stok > 0 ORDER BY nama_barang");
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
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-handshake"></i>
                            </span>
                            Pinjaman Saya
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
                                <h4 class="card-title">Daftar Peminjaman Saya</h4>
                                <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPinjam">
                                    <i class="mdi mdi-plus"></i> Pinjam Barang
                                </button>
                            </div>

                            <?php if ($total > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle" id="tablePinjaman">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>ID Pinjam</th>
                                                <th>Barang</th>
                                                <th>Tgl Pinjam</th>
                                                <th>Jam Pinjam</th>
                                                <th>Tgl Kembali</th>
                                                <th>Jam Kembali</th>
                                                <th>Durasi</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th width="80">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['id_peminjaman'] ?></td>
                                                    <td><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                                                    <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                                    <td><?= $row['jam_pinjam'] ?></td>
                                                    <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                                                    <td><?= $row['jam_kembali'] ?? '-' ?></td>
                                                    <td><?= $row['durasi_jam'] ?> jam</td>
                                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php
                                                        $badge = '';
                                                        switch($row['status']) {
                                                            case 'dipinjam': $badge = 'badge bg-warning text-dark'; break;
                                                            case 'dikembalikan': $badge = 'badge bg-success'; break;
                                                            case 'batal': $badge = 'badge bg-danger'; break;
                                                            default: $badge = 'badge bg-secondary';
                                                        }
                                                        ?>
                                                        <span class="<?= $badge ?> px-3 py-2"><?= ucfirst($row['status']) ?></span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info text-white" onclick="detailPinjaman(<?= $row['id_peminjaman'] ?>)" title="Detail">
                                                            <i class="mdi mdi-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="mdi mdi-handshake mdi-48px text-muted mb-3"></i>
                                    <h5>Belum Ada Peminjaman</h5>
                                    <p class="text-muted">Silakan pinjam barang terlebih dahulu</p>
                                    <button class="btn btn-gradient-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalTambahPinjam">Pinjam Barang</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php include '../../partials/footer.php'; ?>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL TAMBAH PINJAM ===================== -->
    <div class="modal fade" id="modalTambahPinjam" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus-circle"></i> Form Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTambahPinjam" method="POST" action="../../action/peminjaman_saya/store.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pilih Barang</label>
                            <select name="id_barang" id="barang_tambah" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php while($b = mysqli_fetch_assoc($barang)): ?>
                                    <option value="<?= $b['id_barang'] ?>" data-harga="<?= $b['harga_per_jam'] ?>" data-stok="<?= $b['stok'] ?>">
                                        <?= htmlspecialchars($b['nama_barang']) ?> - Rp <?= number_format($b['harga_per_jam']) ?>/jam (Stok: <?= $b['stok'] ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tgl_tambah" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Jam Pinjam</label>
                                <input type="time" name="jam_pinjam" id="jam_tambah" class="form-control" value="<?= date('H:i') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Durasi Sewa (Jam)</label>
                            <input type="number" name="durasi_jam" id="durasi_tambah" class="form-control" min="1" value="1" required>
                        </div>

                        <div class="alert alert-info" id="previewTambah">
                            <small><strong>Preview:</strong></small><br>
                            <small>Tanggal Kembali: <span id="preview_tgl_kembali">-</span></small><br>
                            <small>Jam Kembali: <span id="preview_jam_kembali">-</span></small><br>
                            <small>Total Harga: <span id="preview_total">Rp 0</span></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tombol" class="btn btn-gradient-primary">Ajukan Peminjaman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL DETAIL ===================== -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title"><i class="mdi mdi-information"></i> Detail Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <div class="text-center">Loading...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../partials/script.php' ?>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablePinjaman').DataTable({
                language: { search: "Cari:", lengthMenu: "Tampilkan _MENU_ data", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data" },
                pageLength: 10,
                order: [[1, 'desc']]
            });

            // Preview di modal tambah
            function updatePreviewTambah() {
                const harga = $('#barang_tambah option:selected').data('harga');
                const durasi = parseInt($('#durasi_tambah').val()) || 0;
                const tgl = $('#tgl_tambah').val();
                const jam = $('#jam_tambah').val();

                if (harga && durasi > 0) {
                    $('#preview_total').text('Rp ' + (harga * durasi).toLocaleString('id-ID'));
                } else {
                    $('#preview_total').text('Rp 0');
                }

                if (tgl && jam && durasi > 0) {
                    let date = new Date(tgl + 'T' + jam);
                    date.setHours(date.getHours() + durasi);
                    $('#preview_tgl_kembali').text(date.toLocaleDateString('id-ID'));
                    $('#preview_jam_kembali').text(date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));
                } else {
                    $('#preview_tgl_kembali').text('-');
                    $('#preview_jam_kembali').text('-');
                }
            }

            $('#barang_tambah, #durasi_tambah, #tgl_tambah, #jam_tambah').on('change input', updatePreviewTambah);
            updatePreviewTambah();
        });

        function detailPinjaman(id) {
            $('#modalDetail').modal('show');
            $('#detailContent').html('<div class="text-center">Loading...</div>');
            $.ajax({
                url: '../../action/peminjaman_saya/get_detail.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        let statusClass = data.status == 'dipinjam' ? 'warning' : (data.status == 'dikembalikan' ? 'success' : 'danger');
                        let html = `
                            <table class="table table-borderless">
                                <tr><td width="120"><strong>ID Peminjaman</strong></td><td>: #${data.id_peminjaman}</td></tr>
                                <tr><td><strong>Barang</strong></td><td>: ${data.nama_barang}</td></tr>
                                <tr><td><strong>Tanggal Pinjam</strong></td><td>: ${data.tanggal_pinjam}</td></tr>
                                <tr><td><strong>Jam Pinjam</strong></td><td>: ${data.jam_pinjam}</td></tr>
                                <tr><td><strong>Tanggal Kembali</strong></td><td>: ${data.tanggal_kembali || '-'}</td></tr>
                                <tr><td><strong>Jam Kembali</strong></td><td>: ${data.jam_kembali || '-'}</td></tr>
                                <tr><td><strong>Durasi</strong></td><td>: ${data.durasi_jam} jam</td></tr>
                                <tr><td><strong>Total Harga</strong></td><td>: Rp ${data.total_harga}</td></tr>
                                <tr><td><strong>Status</strong></td><td>: <span class="badge bg-${statusClass}">${data.status}</span></td></tr>
                                <tr><td><strong>Dibuat</strong></td><td>: ${data.created_at}</td></tr>
                            </table>
                        `;
                        $('#detailContent').html(html);
                    } else {
                        $('#detailContent').html('<div class="text-danger">Gagal mengambil data</div>');
                    }
                },
                error: function() {
                    $('#detailContent').html('<div class="text-danger">Error</div>');
                }
            });
        }
    </script>
</body>
</html>