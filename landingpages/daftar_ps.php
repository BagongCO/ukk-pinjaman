<?php
include "../backend/app.php";

$data = mysqli_query($connect, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>

<head>

    <title>Daftar PlayStation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <?php include "partials/navbar.php"; ?>

    <div class="container mt-5">

        <h2 class="mb-4">
            Daftar PlayStation
        </h2>

        <div class="row">

            <?php while ($row = mysqli_fetch_assoc($data)) { ?>

                <div class="col-md-4 mb-4">

                    <div class="card">

                        <img src="../storage/barang/<?php echo $row['foto']; ?>" class="card-img-top">

                        <div class="card-body">

                            <h5><?php echo $row['nama_barang']; ?></h5>

                            <p>
                                Rp <?php echo number_format($row['harga_per_jam']); ?> / jam
                            </p>

                            <a href="detail_ps.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-primary">
                                Detail
                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

    <?php include "partials/footer.php"; ?>

</body>

</html>