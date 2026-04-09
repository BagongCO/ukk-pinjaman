<?php

if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Silahkan login dahulu');
        window.location='../auth/login.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rental PS</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="../../template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../template/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../../template/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../../template/assets/vendors/font-awesome/css/font-awesome.min.css">

    <!-- Plugin css -->
    <link rel="stylesheet" href="../../template/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">

    <!-- Layout styles -->
    <link rel="stylesheet" href="../../template/assets/css/style.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../../template/assets/images/joystik.png" />
</head>