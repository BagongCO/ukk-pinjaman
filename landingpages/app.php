<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config/connection.php';
include __DIR__ . '/../config/escapeString.php';
