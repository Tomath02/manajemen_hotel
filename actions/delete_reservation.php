<?php
session_start();
include '../config.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    $query = "DELETE FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo "<script>alert('Reservasi berhasil dihapus!'); window.location.href = '../pages/reservations.php';</script>";
    } else {
        echo "Gagal menghapus reservasi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID reservasi tidak ditemukan!";
}
