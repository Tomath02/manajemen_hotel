<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];

    $query = "INSERT INTO rooms (room_number, type, price_per_night, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssds", $room_number, $type, $price_per_night, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Kamar berhasil ditambahkan!'); window.location.href = '../pages/rooms.php';</script>";
    } else {
        echo "Gagal menambahkan kamar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses ditolak!";
}
