<?php
session_start();

// Perbaiki path ke config
include_once __DIR__ . '/../../config/connection.php';

// Cek apakah user login
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    // simpan log logout sebelum destroy session
    $id_user = $_SESSION['id_user'] ?? 0;
    $username = $_SESSION['username'] ?? '';
    $role = $_SESSION['role'] ?? '';
    $aktivitas = "Logout dari sistem";

    if ($id_user > 0) {
        mysqli_query($connect, "
            INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu)
            VALUES ('$id_user', '$username', '$role', '$aktivitas', NOW())
        ");
    }
}

// hapus session
session_destroy();

echo "
<script>
    alert('Berhasil logout');
    window.location.href='login';
</script>
";
exit;
?>