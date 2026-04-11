<?php
session_start();

include '../../app.php';

$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($connect, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if ($data) {

    // Gunakan password_verify() untuk cek password yang sudah di-hash
    if (password_verify($password, $data['password'])) {

        // SESSION LOGIN
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role_id'] = $data['id_role'];

        // Log aktivitas login
        $id_user = $data['id_user'];
        $username = $data['username'];
        
        if ($data['id_role'] == 1) {
            $_SESSION['role'] = 'admin';
            $role = 'admin';
        } elseif ($data['id_role'] == 2) {
            $_SESSION['role'] = 'petugas';
            $role = 'petugas';
        } elseif ($data['id_role'] == 3) {
            $_SESSION['role'] = 'peminjam';
            $role = 'peminjam';
        }

        // Catat log login
        $aktivitas = "Login ke sistem";
        mysqli_query($connect, "
            INSERT INTO log_aktivitas (id_user, username, role, aktivitas, waktu) 
            VALUES ('$id_user', '$username', '$role', '$aktivitas', NOW())
        ");

        // Redirect sesuai role
        if ($data['id_role'] == 1) {
            header("Location: ../dashboard/index.php");
        } elseif ($data['id_role'] == 2) {
            header("Location: ../dashboard/index.php");
        } elseif ($data['id_role'] == 3) {
            header("Location: ../dashboard/index.php");
        } else {
            header("Location: ../dashboard/index.php");
        }
        exit;
        
    } else {
        $_SESSION['error'] = "Password salah!";
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Username tidak ditemukan!";
    header("Location: login.php");
    exit;
}
?>