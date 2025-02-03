<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../config.php';
include '../templates/header.php';

// Hitung total tamu
$result = $conn->query("SELECT COUNT(*) AS total FROM guests");
$total_guests = $result->fetch_assoc()['total'];

// Hitung total kamar
$result = $conn->query("SELECT COUNT(*) AS total FROM rooms");
$total_rooms = $result->fetch_assoc()['total'];

// Hitung kamar berdasarkan status
$available_rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='Available'")->fetch_assoc()['total'];
$occupied_rooms = $conn->query("SELECT COUNT(*) AS total FROM rooms WHERE status='Occupied'")->fetch_assoc()['total'];

// Hitung total reservasi
$result = $conn->query("SELECT COUNT(*) AS total FROM reservations");
$total_reservations = $result->fetch_assoc()['total'];

// Hitung total pembayaran
$result = $conn->query("SELECT COUNT(*) AS total FROM payments");
$total_payments = $result->fetch_assoc()['total'];

// Hitung total layanan tambahan
$result = $conn->query("SELECT COUNT(*) AS total FROM services");
$total_services = $result->fetch_assoc()['total'];

// Hitung total pegawai
$result = $conn->query("SELECT COUNT(*) AS total FROM employees");
$total_employees = $result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-3" id="toggleSidebar">☰</button>
            <a class="navbar-brand" href="#">Hotel Dashboard</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <button class="btn btn-light close-btn" id="closeSidebar">←</button>
        <h5 class="text-center text-light mt-3">Menu</h5>
        <a href="guests.php">Manajemen Tamu</a>
        <a href="rooms.php">Manajemen Kamar</a>
        <a href="reservations.php">Manajemen Reservasi</a>
        <a href="payments.php">Manajemen Pembayaran</a>
        <a href="services.php">Manajemen Layanan</a>
        <a href="employees.php">Manajemen Pegawai</a>
        <a href="../logout.php" class="text-danger">Logout</a>
    </div>

    <!-- Overlay (hanya menutupi main content) -->
    <div id="overlay" class="overlay"></div>

    <!-- Main Content -->
    <div class="container-fluid content" id="main-content">
        <h2 class="text-center text-primary my-4">Dashboard</h2>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Statistik Hotel</div>
                    <div class="card-body text-center">
                        <p><strong>Tamu:</strong> <?= $total_guests; ?></p>
                        <p><strong>Total Kamar:</strong> <?= $total_rooms; ?> (Available: <?= $available_rooms; ?>, Occupied: <?= $occupied_rooms; ?>)</p>
                        <p><strong>Total Reservasi:</strong> <?= $total_reservations; ?></p>
                        <p><strong>Total Pembayaran:</strong> <?= $total_payments; ?></p>
                        <p><strong>Total Layanan Tambahan:</strong> <?= $total_services; ?></p>
                        <p><strong>Total Pegawai:</strong> <?= $total_employees; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Kamar & Reservasi</div>
                    <div class="card-body text-center">
                        <p><strong>Kamar Tersedia:</strong> <?= $available_rooms; ?></p>
                        <p><strong>Kamar Terisi:</strong> <?= $occupied_rooms; ?></p>
                        <p><strong>Total Reservasi:</strong> <?= $total_reservations; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-custom">
                    <div class="card-header">Keuangan</div>
                    <div class="card-body text-center">
                        <p><strong>Total Pembayaran:</strong> <?= $total_payments; ?></p>
                        <p><strong>Total Layanan Tambahan:</strong> <?= $total_services; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Sidebar -->
    <script>
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const overlay = document.getElementById("overlay");
        const openBtn = document.getElementById("toggleSidebar");
        const closeBtn = document.getElementById("closeSidebar");

        openBtn.addEventListener("click", function () {
            sidebar.classList.add("sidebar-show");
            overlay.classList.add("overlay-active");
        });

        closeBtn.addEventListener("click", function () {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });

        overlay.addEventListener("click", function () {
            sidebar.classList.remove("sidebar-show");
            overlay.classList.remove("overlay-active");
        });
    </script>

</body>

</html>
