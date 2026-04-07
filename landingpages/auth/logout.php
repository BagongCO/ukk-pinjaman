<?php
session_start();

// hapus semua session
session_unset();
session_destroy();

// kembali ke landing page
header("Location: ../index.php");
exit;
