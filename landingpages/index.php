<?php
include "../backend/app.php";

$ps = mysqli_query($connect, "SELECT * FROM barang LIMIT 6");
?>

<!DOCTYPE html>
<html>

<head>

    <title>Rental PS EON</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero {
            height: 90vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            align-items: center;
            color: white;
        }

        .card-ps:hover {
            transform: translateY(-10px);
            transition: 0.3s;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <?php include "partials/navbar.php"; ?>

    <!-- HERO -->

    <section class="hero">

        <div class="container text-center">

            <h1 class="display-4 fw-bold">
                Rental PS EON
            </h1>

            <p class="lead">
                Tempat terbaik untuk bermain PlayStation
            </p>

            <a href="daftar_ps.php" class="btn btn-light btn-lg mt-3">
                Lihat PlayStation
            </a>

        </div>

    </section>


    <!-- LIST PS -->

    <section class="py-5">

        <div class="container">

            <h2 class="text-center mb-5">
                PlayStation Tersedia
            </h2>

            <div class="row">

                <?php while ($row = mysqli_fetch_assoc($ps)) { ?>

                    <div class="col-md-4 mb-4">

                        <div class="card card-ps">

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

    </section>

    <?php include "partials/footer.php"; ?>

</body>

</html>