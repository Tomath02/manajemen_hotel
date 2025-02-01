<?php
session_start();
include '../config.php';

if (!isset($_GET['room_id'])) {
    echo "ID kamar tidak ditemukan!";
    exit();
}

$room_id = $_GET['room_id'];

$query = "DELETE FROM rooms WHERE room_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    echo "<script>alert('Kamar berhasil dihapus!'); window.location.href = '../pages/rooms.php';</script>";
} else {
    echo "Gagal menghapus kamar: " . $stmt->error;
}

$stmt->close();
$conn->close();
