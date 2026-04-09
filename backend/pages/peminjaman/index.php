<?php
include '../../app.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/index.php");
    exit;
}

// Ambil data peminjaman dengan JOIN ke users
$query = "SELECT 
            p.*,
            b.nama_barang,
            b.harga_per_jam,
            u.username
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          LEFT JOIN users u ON p.id_user = u.id_user
          ORDER BY p.id_peminjaman DESC";

$data = mysqli_query($connect, $query);

if (!$data) {
    die("Query Error: " . mysqli_error($connect));
}

$totalPeminjaman = mysqli_num_rows($data);

// Ambil data untuk dropdown barang
$queryBarang = mysqli_query($connect, "SELECT id_barang, nama_barang, harga_per_jam FROM barang ORDER BY nama_barang");
$barangList = [];
while ($barang = mysqli_fetch_assoc($queryBarang)) {
    $barangList[] = $barang;
}

// Ambil data users untuk dropdown (opsional, tapi lebih baik)
$queryUsers = mysqli_query($connect, "SELECT id_user, username FROM users ORDER BY username");
$userList = [];
while ($user = mysqli_fetch_assoc($queryUsers)) {
    $userList[] = $user;
}
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
                            Data Peminjaman
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
                                <h4 class="card-title">Daftar Peminjaman</h4>
                                <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                                    + Tambah Peminjaman
                                </button>
                            </div>

                            <?php if ($totalPeminjaman > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle" id="tablePeminjaman">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">No</th>
                                                <th>ID Pinjam</th>
                                                <th>Peminjam</th>
                                                <th>Barang</th>
                                                <th>Tgl Pinjam</th>
                                                <th>Jam Pinjam</th>
                                                <th>Tgl Kembali</th>
                                                <th>Jam Kembali</th>
                                                <th>Durasi</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th width="150">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['id_peminjaman'] ?></td>
                                                    <td><?= htmlspecialchars($row['username'] ?? 'User #'.$row['id_user']) ?></td>
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
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-info text-white" onclick="detailPeminjaman(<?= $row['id_peminjaman'] ?>)" title="Detail">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning" onclick="editPeminjaman(<?= $row['id_peminjaman'] ?>)" title="Edit">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="hapusPeminjaman(<?= $row['id_peminjaman'] ?>, '<?= addslashes($row['nama_barang'] ?? '') ?>')" title="Hapus">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="mdi mdi-handshake mdi-48px text-muted mb-3"></i>
                                    <h5>Belum Ada Data Peminjaman</h5>
                                    <button class="btn btn-gradient-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Peminjaman</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php include '../../partials/footer.php'; ?>
            </div>
        </div>
    </div>

    <!-- ====================== MODAL TAMBAH ====================== -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title"><i class="mdi mdi-plus"></i> Tambah Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTambah" method="POST" action="../../action/peminjaman/store.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Peminjam</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">-- Pilih Peminjam --</option>
                                <?php foreach($userList as $user): ?>
                                    <option value="<?= $user['id_user'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Barang</label>
                            <select name="id_barang" id="barang_tambah" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($barangList as $barang): ?>
                                    <option value="<?= $barang['id_barang'] ?>" data-harga="<?= $barang['harga_per_jam'] ?>">
                                        <?= htmlspecialchars($barang['nama_barang']) ?> - Rp <?= number_format($barang['harga_per_jam']) ?>/jam
                                    </option>
                                <?php endforeach; ?>
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
                        <button type="submit" name="tombol" class="btn btn-gradient-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ====================== MODAL DETAIL ====================== -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-info text-white">
                    <h5 class="modal-title"><i class="mdi mdi-info"></i> Detail Peminjaman</h5>
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

    <!-- ====================== MODAL EDIT ====================== -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-warning text-white">
                    <h5 class="modal-title"><i class="mdi mdi-pencil"></i> Edit Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEdit" method="POST" action="">
                    <div class="modal-body" id="editContent">
                        <div class="text-center">Loading...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tombol" class="btn btn-gradient-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ====================== MODAL HAPUS ====================== -->
    <div class="modal fade" id="modalHapus" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formHapus" method="POST" action="../../action/peminjaman/destroy.php">
                    <div class="modal-body text-center">
                        <input type="hidden" name="id_peminjaman" id="hapus_id">
                        <p>Yakin hapus peminjaman <b id="hapus_nama">#</b>?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
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
        // Data untuk dropdown (dari PHP)
        const barangList = <?= json_encode($barangList) ?>;
        const userList = <?= json_encode($userList) ?>;

        $(document).ready(function() {
            // Inisialisasi DataTable
            if ($.fn.DataTable.isDataTable('#tablePeminjaman')) {
                $('#tablePeminjaman').DataTable().destroy();
            }
            
            $('#tablePeminjaman').DataTable({
                language: { 
                    search: "Cari:", 
                    lengthMenu: "Tampilkan _MENU_ data", 
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    emptyTable: "Tidak ada data peminjaman",
                    zeroRecords: "Data tidak ditemukan"
                },
                pageLength: 10,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 11] }
                ]
            });

            // Preview di modal tambah
            function updatePreviewTambah() {
                const barang = $('#barang_tambah option:selected').data('harga');
                const durasi = parseInt($('#durasi_tambah').val()) || 0;
                const tglPinjam = $('#tgl_tambah').val();
                const jamPinjam = $('#jam_tambah').val();

                if (barang && durasi > 0) {
                    $('#preview_total').text('Rp ' + (barang * durasi).toLocaleString('id-ID'));
                } else {
                    $('#preview_total').text('Rp 0');
                }

                if (tglPinjam && jamPinjam && durasi > 0) {
                    let date = new Date(tglPinjam + 'T' + jamPinjam);
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

        function detailPeminjaman(id) {
            $('#modalDetail').modal('show');
            $('#detailContent').html('<div class="text-center">Loading...</div>');
            $.ajax({
                url: '../../action/peminjaman/get_detail.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        let statusClass = data.status == 'dipinjam' ? 'warning' : (data.status == 'dikembalikan' ? 'success' : 'danger');
                        let html = `
                            <div class="info-detail">
                                <table class="table table-borderless">
                                    <tr><td width="120"><strong>ID Peminjaman</strong></td><td>: #${data.id_peminjaman}</td></tr>
                                    <tr><td><strong>Peminjam</strong></td><td>: ${data.username || 'User #'+data.id_user}</td></tr>
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
                            </div>
                        `;
                        $('#detailContent').html(html);
                    } else {
                        $('#detailContent').html('<div class="text-danger">Gagal mengambil data</div>');
                    }
                },
                error: function() {
                    $('#detailContent').html('<div class="text-danger">Error mengambil data</div>');
                }
            });
        }

        function editPeminjaman(id) {
            $('#modalEdit').modal('show');
            $('#editContent').html('<div class="text-center">Loading...</div>');
            
            $.ajax({
                url: '../../action/peminjaman/get_edit.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        // Buat option barang
                        let barangOptions = '<option value="">-- Pilih Barang --</option>';
                        for(let i = 0; i < barangList.length; i++) {
                            let selected = (barangList[i].id_barang == data.id_barang) ? 'selected' : '';
                            barangOptions += `<option value="${barangList[i].id_barang}" data-harga="${barangList[i].harga_per_jam}" ${selected}>${barangList[i].nama_barang} - Rp ${new Intl.NumberFormat('id-ID').format(barangList[i].harga_per_jam)}/jam</option>`;
                        }
                        
                        // Buat option user
                        let userOptions = '<option value="">-- Pilih Peminjam --</option>';
                        for(let i = 0; i < userList.length; i++) {
                            let selected = (userList[i].id_user == data.id_user) ? 'selected' : '';
                            userOptions += `<option value="${userList[i].id_user}" ${selected}>${userList[i].username}</option>`;
                        }
                        
                        let html = `
                            <input type="hidden" name="id_peminjaman" value="${data.id_peminjaman}">
                            <div class="mb-3">
                                <label>Peminjam</label>
                                <select name="id_user" id="user_edit" class="form-select" required>
                                    ${userOptions}
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Barang</label>
                                <select name="id_barang" id="barang_edit" class="form-select" required>
                                    ${barangOptions}
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" id="tgl_edit" class="form-control" value="${data.tanggal_pinjam}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Jam Pinjam</label>
                                    <input type="time" name="jam_pinjam" id="jam_edit" class="form-control" value="${data.jam_pinjam}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Durasi Sewa (Jam)</label>
                                <input type="number" name="durasi_jam" id="durasi_edit" class="form-control" min="1" value="${data.durasi_jam}" required>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="dipinjam" ${data.status == 'dipinjam' ? 'selected' : ''}>Dipinjam</option>
                                    <option value="dikembalikan" ${data.status == 'dikembalikan' ? 'selected' : ''}>Dikembalikan</option>
                                    <option value="batal" ${data.status == 'batal' ? 'selected' : ''}>Batal</option>
                                </select>
                            </div>
                            <div class="alert alert-info" id="previewEdit">
                                <small><strong>Preview:</strong></small><br>
                                <small>Tanggal Kembali: <span id="preview_tgl_edit">-</span></small><br>
                                <small>Jam Kembali: <span id="preview_jam_edit">-</span></small><br>
                                <small>Total Harga: <span id="preview_total_edit">Rp 0</span></small>
                            </div>
                        `;
                        $('#editContent').html(html);
                        $('#formEdit').attr('action', `../../action/peminjaman/update.php?id=${data.id_peminjaman}`);
                        
                        window.editHargaPerJam = data.harga_per_jam;
                        updatePreviewEdit();
                        
                        $('#barang_edit, #durasi_edit, #tgl_edit, #jam_edit').on('change input', updatePreviewEdit);
                    } else {
                        $('#editContent').html('<div class="text-danger">Gagal mengambil data</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    $('#editContent').html('<div class="text-danger">Error mengambil data: ' + error + '</div>');
                }
            });
        }

        function updatePreviewEdit() {
            const selectedOption = $('#barang_edit option:selected');
            let harga = selectedOption.data('harga');
            if (!harga && window.editHargaPerJam) {
                harga = window.editHargaPerJam;
            }
            const durasi = parseInt($('#durasi_edit').val()) || 0;
            const tglPinjam = $('#tgl_edit').val();
            const jamPinjam = $('#jam_edit').val();

            if (harga && durasi > 0) {
                $('#preview_total_edit').text('Rp ' + (harga * durasi).toLocaleString('id-ID'));
            } else {
                $('#preview_total_edit').text('Rp 0');
            }

            if (tglPinjam && jamPinjam && durasi > 0) {
                let date = new Date(tglPinjam + 'T' + jamPinjam);
                date.setHours(date.getHours() + durasi);
                $('#preview_tgl_edit').text(date.toLocaleDateString('id-ID'));
                $('#preview_jam_edit').text(date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }));
            } else {
                $('#preview_tgl_edit').text('-');
                $('#preview_jam_edit').text('-');
            }
        }

        function hapusPeminjaman(id, nama) {
            $('#hapus_id').val(id);
            $('#hapus_nama').text('#' + id + ' - ' + nama);
            $('#modalHapus').modal('show');
        }
    </script>

    <style>
        .info-detail table tr td { padding: 5px 0; }
        .modal-header .btn-close { filter: brightness(0) invert(1); }
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 15px;
        }
    </style>

</body>
</html>