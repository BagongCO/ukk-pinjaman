<?php
include '../../../app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_user = $_POST['id_user'];
    $nama = mysqli_real_escape_string($connect, $_POST['nama']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password'];
    $id_role = $_POST['id_role']; // 1=admin, 2=petugas, 3=peminjam

    /* ===============================
       CEK PASSWORD DIISI ATAU TIDAK
    =============================== */
    if (!empty($password)) {
        // jika password diisi, hash dan update
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = mysqli_query($connect, "
            UPDATE users SET
                nama='$nama',
                username='$username',
                password='$password_hash',
                id_role='$id_role'
            WHERE id_user='$id_user'
        ");
    } else {
        // jika password kosong, tidak update password
        $query = mysqli_query($connect, "
            UPDATE users SET
                nama='$nama',
                username='$username',
                id_role='$id_role'
            WHERE id_user='$id_user'
        ");
    }

    if ($query) {
        /* ===============================
           LOG AKTIVITAS
        ================================ */
        mysqli_query($connect, "
            INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu)
            VALUES ('{$_SESSION['id_user']}', '{$_SESSION['username']}', '{$_SESSION['role']}', 'Mengedit akun id=$id_user', NOW())
        ");
        $_SESSION['success'] = "Data berhasil diupdate";
    } else {
        $_SESSION['error'] = "Gagal mengupdate data: " . mysqli_error($connect);
    }

    header("Location: ../index.php");
    exit;
}
?>