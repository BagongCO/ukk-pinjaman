<?php
include '../../app.php';

$id = $_GET['id'];

$data = mysqli_query($connect, "
    SELECT 
        p.*, 
        u.nama AS nama_user,
        b.nama_barang,
        b.harga_per_jam
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN barang b ON p.id_barang = b.id_barang
    WHERE p.id_peminjaman = '$id'
");

$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pengembalian</title>
    <style>
        body {
            font-family: Arial;
        }

        .box {
            width: 600px;
            margin: auto;
            border: 1px solid #000;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="box">
        <h2>Laporan Pengembalian Barang</h2>
        <hr>

        <table>
            <tr>
                <td>Nama Peminjam</td>
                <td>: <?= htmlspecialchars($row['nama_user']) ?></td>
            </tr>
            <tr>
                <td>Barang</td>
                <td>: <?= htmlspecialchars($row['nama_barang']) ?></td>
            </tr>
            <tr>
                <td>Tanggal Pinjam</td>
                <td>: <?= $row['tanggal_pinjam'] ?> <?= $row['jam_pinjam'] ?></td>
            </tr>
            <tr>
                <td>Tanggal Kembali</td>
                <td>: <?= $row['tanggal_kembali'] ?> <?= $row['jam_kembali'] ?></td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>: <?= $row['durasi_jam'] ?> Jam</td>
            </tr>
            <tr>
                <td>Total Harga</td>
                <td>: Rp <?= number_format($row['total_harga']) ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: <?= $row['status'] ?></td>
            </tr>
        </table>

        <br><br>
        <table>
            <tr>
                <td width="50%" align="center">
                    Petugas<br><br><br>
                    ( __________ )
                </td>
                <td width="50%" align="center">
                    Peminjam<br><br><br>
                    ( __________ )
                </td>
            </tr>
        </table>
    </div>

</body>

</html>