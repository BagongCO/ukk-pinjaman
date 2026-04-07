<?php
session_start();
include '../../app.php';

// simpan log logout sebelum destroy session
$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$aktivitas = "Logout dari sistem";

mysqli_query($connect, "
    INSERT INTO log_aktivitas (id_user, username, role, aktivitas)
    VALUES ('$id_user', '$username', '$role', '$aktivitas')
");

// hapus session
session_destroy();

echo "
<script>
    alert('Berhasil logout');
    window.location.href='login.php';
</script>
";
exit;
