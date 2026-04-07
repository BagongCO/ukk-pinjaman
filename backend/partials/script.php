<!-- plugins:js -->
<script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="../../template/assets/vendors/chart.js/chart.umd.js"></script>
<script src="../../template/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="../../template/assets/js/off-canvas.js"></script>
<script src="../../template/assets/js/misc.js"></script>
<script src="../../template/assets/js/settings.js"></script>
<script src="../../template/assets/js/todolist.js"></script>
<script src="../../template/assets/js/jquery.cookie.js"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="../../template/assets/js/dashboard.js"></script>
<!-- End custom js for this page -->
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tableOperator').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "language": {
                "lengthMenu": "Show _MENU_ entries per page",
                "search": "Search:",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "next": "Next",
                    "previous": "Previous"
                },
                "zeroRecords": "Data tidak ditemukan",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tablePeminjaman').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [
                [0, "desc"]
            ],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data",
                "search": "Cari:",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "Berikutnya",
                    "previous": "Sebelumnya"
                },
                "zeroRecords": "Data tidak ditemukan",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            }
        });
    });
</script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tableBarang').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Data kosong",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "›",
                    "previous": "‹"
                }
            }
        });
    });
</script>