<?php
session_start();
include '../config.php';

if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];

    $query = "DELETE FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        echo "<script>alert('Layanan berhasil dihapus!'); window.location.href = '../pages/services.php';</script>";
    } else {
        echo "Gagal menghapus layanan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("ID layanan tidak ditemukan!");
}
