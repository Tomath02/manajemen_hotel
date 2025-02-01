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
    <title>Dashboard</title>
</head>

<body>
    <h2>Dashboard</h2>
    <div>
        <h3>Statistik Hotel</h3>
        <p>Total Tamu: <?= $total_guests; ?></p>
        <p>Total Kamar: <?= $total_rooms; ?> (Available: <?= $available_rooms; ?>, Occupied: <?= $occupied_rooms; ?>)</p>
        <p>Total Reservasi: <?= $total_reservations; ?></p>
        <p>Total Pembayaran: <?= $total_payments; ?></p>
        <p>Total Layanan Tambahan: <?= $total_services; ?></p>
        <p>Total Pegawai: <?= $total_employees; ?></p>
    </div>

    <h3>Manajemen</h3>
    <ul>
        <li><a href="guests.php">Manajemen Tamu</a></li>
        <li><a href="rooms.php">Manajemen Kamar</a></li>
        <li><a href="reservations.php">Manajemen Reservasi</a></li>
        <li><a href="payments.php">Manajemen Pembayaran</a></li>
        <li><a href="services.php">Manajemen Layanan</a></li>
        <li><a href="employees.php">Manajemen Pegawai</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</body>

</html>