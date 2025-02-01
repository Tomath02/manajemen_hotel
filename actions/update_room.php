<?php
session_start();
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $type = $_POST['type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];

    $query = "UPDATE rooms SET room_number = ?, type = ?, price_per_night = ?, status = ? WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsi", $room_number, $type, $price_per_night, $status, $room_id);

    if ($stmt->execute()) {
        echo "<script>alert('Kamar berhasil diperbarui!'); window.location.href = '../pages/rooms.php';</script>";
    } else {
        echo "Gagal memperbarui kamar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses ditolak!";
}
