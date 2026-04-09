<?php
include '../../app.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/index.php");
    exit;
}

/* ===============================
   AMBIL DATA PEMINJAMAN
   JOIN dengan tabel user dan barang
================================ */
$query = "SELECT 
            p.*,
            b.nama_barang,
            b.harga_per_jam,
            u.username as nama_user
          FROM peminjaman p
          LEFT JOIN barang b ON p.id_barang = b.id_barang
          LEFT JOIN user u ON p.id_user = u.id_user
          ORDER BY p.id_peminjaman DESC";

$data = mysqli_query($connect, $query);

if (!$data) {
    die("Query Error : " . mysqli_error($connect));
}

$totalPeminjaman = mysqli_num_rows($data);
?>

<?php include '../../partials/header.php' ?>

<body>
    <div class="container-scroller">

        <?php include '../../partials/navbar.php' ?>

        <div class="container-fluid page-body-wrapper">

            <?php include '../../partials/sidebar.php' ?>

            <div class="main-panel">
                <div class="content-wrapper">

                    <!-- HEADER -->
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <i class="mdi mdi-handshake"></i>
                            </span>
                            Data Peminjaman
                        </h3>
                    </div>

                    <!-- ALERT SUCCESS -->
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="mdi mdi-check-circle me-2"></i>
                            <?= htmlspecialchars($_SESSION['success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <!-- ALERT ERROR -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            <?= htmlspecialchars($_SESSION['error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                                <h4 class="card-title mb-2 mb-sm-0">
                                    <i class="mdi mdi-format-list-bulleted"></i> Daftar Peminjaman
                                </h4>
                                <div class="d-flex gap-2">
                                    <a href="create.php" class="btn btn-gradient-primary">
                                        <i class="mdi mdi-plus"></i> Tambah Peminjaman
                                    </a>
                                    <a href="laporan.php" class="btn btn-gradient-info">
                                        <i class="mdi mdi-file-document"></i> Laporan
                                    </a>
                                </div>
                            </div>

                            <!-- Info Total -->
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="mdi mdi-information-outline"></i>
                                    <strong>Total Peminjaman: <?= $totalPeminjaman ?></strong>
                                </div>
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
                                                <th width="200">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $row['id_peminjaman'] ?></td>
                                                    <td><?= htmlspecialchars($row['nama_user'] ?? 'User tidak ditemukan') ?></td>
                                                    <td><?= htmlspecialchars($row['nama_barang'] ?? 'Barang tidak ditemukan') ?></td>
                                                    <td><?= $row['tanggal_pinjam'] ? date('d-m-Y', strtotime($row['tanggal_pinjam'])) : '-' ?></td>
                                                    <td><?= $row['jam_pinjam'] ?? '-' ?></td>
                                                    <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                                                    <td><?= $row['jam_kembali'] ?? '-' ?></td>
                                                    <td><?= $row['durasi_jam'] ?> jam</td>
                                                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php 
                                                        switch($row['status']) {
                                                            case 'dipinjam':
                                                                echo '<span class="badge bg-warning text-dark px-3 py-2">Dipinjam</span>';
                                                                break;
                                                            case 'dikembalikan':
                                                                echo '<span class="badge bg-success px-3 py-2">Dikembalikan</span>';
                                                                break;
                                                            case 'batal':
                                                                echo '<span class="badge bg-danger px-3 py-2">Batal</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge bg-secondary px-3 py-2">' . $row['status'] . '</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                                            <a href="show.php?id=<?= $row['id_peminjaman'] ?>" 
                                                               class="btn btn-sm btn-info text-white" 
                                                               title="Detail">
                                                                <i class="mdi mdi-eye"></i>
                                                            </a>
                                                            <a href="edit.php?id=<?= $row['id_peminjaman'] ?>" 
                                                               class="btn btn-sm btn-warning" 
                                                               title="Edit">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-secondary" 
                                                                    title="Cetak Struk"
                                                                    onclick="printStruk(<?= $row['id_peminjaman'] ?>)">
                                                                <i class="mdi mdi-printer"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    title="Hapus"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#modalHapus<?= $row['id_peminjaman'] ?>">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- MODAL HAPUS -->
                                                <div class="modal fade" id="modalHapus<?= $row['id_peminjaman'] ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="mdi mdi-alert"></i> Hapus Peminjaman
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="../../action/peminjaman/destroy.php" method="POST">
                                                                <div class="modal-body text-center">
                                                                    <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman'] ?>">
                                                                    <p>
                                                                        Yakin hapus peminjaman 
                                                                        <b>#<?= $row['id_peminjaman'] ?></b> ?
                                                                    </p>
                                                                    <p class="text-muted small">
                                                                        Barang: <?= htmlspecialchars($row['nama_barang'] ?? '-') ?>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer justify-content-center">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="mdi mdi-handshake mdi-48px text-muted mb-3"></i>
                                    <h5>Belum Ada Data Peminjaman</h5>
                                    <p class="text-muted">Mulai dengan menambahkan peminjaman baru</p>
                                    <a href="create.php" class="btn btn-gradient-primary mt-2">
                                        <i class="mdi mdi-plus"></i> Tambah Peminjaman
                                    </a>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                </div>

                <?php include '../../partials/footer.php' ?>

            </div>
        </div>
    </div>

    <!-- Modal Cetak Struk -->
    <div class="modal fade" id="strukModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title">
                        <i class="mdi mdi-receipt"></i> Struk Peminjaman
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="strukContent">
                    <!-- Konten struk akan diisi via JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-gradient-primary" onclick="printStrukModal()">
                        <i class="mdi mdi-printer"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../partials/script.php' ?>

    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablePeminjaman').DataTable({
                language: {
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        previous: "<",
                        next: ">",
                        last: "Terakhir"
                    }
                },
                pageLength: 10,
                order: [[1, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [0, 11] }
                ]
            });
        });

        function printStruk(id) {
            $.ajax({
                url: '../../action/peminjaman/print_struk.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        let strukHtml = `
                            <div class="struk-container" style="font-family: monospace;">
                                <div style="text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 15px;">
                                    <div style="font-size: 18px; font-weight: bold;">PEMINJAMAN ALAT BERAT</div>
                                    <div>Jl. Contoh No. 123, Kota</div>
                                    <div>Telp: (021) 1234567</div>
                                    <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                                    <div>No. Transaksi: #${data.id_peminjaman}</div>
                                    <div>Tanggal: ${data.tanggal_transaksi}</div>
                                </div>
                                
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Peminjam:</span>
                                    <span><strong>${data.nama_user || data.id_user}</strong></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Barang:</span>
                                    <span><strong>${data.nama_barang}</strong></span>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                                
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Tanggal Pinjam:</span>
                                    <span>${data.tanggal_pinjam}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Jam Pinjam:</span>
                                    <span>${data.jam_pinjam}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Tanggal Kembali:</span>
                                    <span>${data.tanggal_kembali || '-'}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Jam Kembali:</span>
                                    <span>${data.jam_kembali || '-'}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Durasi Sewa:</span>
                                    <span>${data.durasi_jam} Jam</span>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                                
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Harga per Jam:</span>
                                    <span>Rp ${data.harga_per_jam}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-weight: bold; font-size: 16px; border-top: 1px solid #000; padding-top: 10px; margin-top: 10px;">
                                    <span>TOTAL HARGA:</span>
                                    <span>Rp ${data.total_harga}</span>
                                </div>
                                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                                
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span>Status:</span>
                                    <span><strong>${data.status}</strong></span>
                                </div>
                                
                                <div style="text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px dashed #000; font-size: 12px;">
                                    <div>Terima kasih atas kepercayaan Anda</div>
                                    <div>Barang wajib dikembalikan tepat waktu</div>
                                    <div>--- Simpan struk ini sebagai bukti ---</div>
                                </div>
                            </div>
                        `;
                        $('#strukContent').html(strukHtml);
                        $('#strukModal').modal('show');
                    } else {
                        alert(data.message || 'Gagal mengambil data struk');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data struk. Pastikan file print_struk.php tersedia.');
                }
            });
        }

        function printStrukModal() {
            window.print();
        }

        // CSS untuk print struk
        const printStyle = document.createElement('style');
        printStyle.textContent = `
            @media print {
                .modal-dialog, .modal-content, .modal-body {
                    position: relative;
                    margin: 0;
                    padding: 0;
                    border: none;
                    box-shadow: none;
                }
                .modal-header, .modal-footer, .btn-close, .btn {
                    display: none !important;
                }
                .struk-container {
                    margin: 0 auto;
                    padding: 20px;
                }
                body * {
                    visibility: hidden;
                }
                .struk-container, .struk-container * {
                    visibility: visible;
                }
                .struk-container {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                }
            }
        `;
        document.head.appendChild(printStyle);
    </script>

    <style>
        .badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
        }
        .struk-container {
            max-width: 400px;
            margin: 0 auto;
        }
        @media print {
            .main-panel, .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }
            .navbar, .sidebar, .page-header, .card-header, .alert, .dataTables_filter, 
            .dataTables_length, .dataTables_paginate, .modal-footer, .btn {
                display: none !important;
            }
        }
    </style>

</body>
</html>