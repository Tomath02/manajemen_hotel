<?php
session_start();

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
} else {
    // Jika belum login, arahkan ke login.php
    header("Location: login.php");
    exit();
}
