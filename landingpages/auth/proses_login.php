<?php
session_start();

include "../../backend/app.php";

$username = mysqli_real_escape_string($connect, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($connect, "
SELECT * FROM users 
WHERE username='$username'
AND id_role = 3
");

$data = mysqli_fetch_assoc($query);

if ($data) {

    if ($password == $data['password']) {

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = 'peminjam';

        header("Location: ../index.php");
    } else {

        echo "<script>
alert('Password salah');
window.location='login.php';
</script>";
    }
} else {

    echo "<script>
alert('Username tidak ditemukan');
window.location='login.php';
</script>";
}
